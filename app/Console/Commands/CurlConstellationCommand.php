<?php

namespace App\Console\Commands;

use App\Services\ConstellationDetailService;
use App\Services\ConstellationService;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class CurlConstellationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'curlConstellation:exec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天的每小時更新資料';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    private $linkList;
    private $starSignList;
    private $concurrency = 12;
    private $counter = 1;
    private $constellationCount;

    public function handle()
    {
        $client = resolve(Client::class);
        $content = $client->request('GET', 'http://astro.click108.com.tw/')->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($content);
        $this->linkList = $crawler->filterXPath('//div[@class="STAR12_BOX"]')->filter('a')->each(function (Crawler $node, $i) {
            return explode('RedirectTo=', urldecode($node->attr('href')))[1];
        });
        $this->starSignList = $crawler->filterXPath('//div[@class="STAR12_BOX"]')->filter('a')->each(function (Crawler $node, $i) {
            return $node->text();
        });
//        print_r($this->starSignList);
//        print_r($this->linkList[0]);
//        $content = $client->request('GET', $this->linkList[0])->getBody()->getContents();
//        $crawler = resolve(Crawler::class);
//        $crawler->addHtmlContent($content);
//        print_r($crawler);


        $this->constellationCount = count($this->linkList);

        $client = new Client();

        $requests = function ($total) use ($client) {
            foreach ($this->linkList as $key => $link) {
                yield function () use ($client, $link) {
                    return $client->getAsync($link);
                };
            }
        };

//        • 當天日期
//        • 星座名稱
//        • 整體運勢的評分及說明
//        • 愛情運勢的評分及說明
//        • 事業運勢的評分及說明
//        • 財運運勢的評分及說明

        $pool = new Pool($client, $requests($this->constellationCount), [
            'concurrency' => $this->concurrency,
            'fulfilled' => function ($response, $index) {

                $res = $response->getBody()->getContents();
                $crawler = new Crawler();
                $crawler->addHtmlContent($res);
                $contents = $crawler->filterXPath('//div[@class="TODAY_CONTENT"]')->filter('p')->each(function (Crawler $node, $i) {
                    return $node->text();
                });

                $constellationData = resolve(ConstellationService::class)->getId($this->starSignList[$index]);

                $data = [];
                $data['date'] = date('Y-m-d');
                $data['constellation_id'] = $constellationData['data']['id'];

                $data['name'] = $contents[0];
                $data['contents'] = $contents[1];
                $data['type'] = 0;
                $result = resolve(ConstellationDetailService::class)->addData($data);
                if ($result['result']) {
                    $this->info('成功');
                } else {
                    $this->error($result['error']['message']);
                }

                $data['name'] = $contents[2];
                $data['contents'] = $contents[3];
                $data['type'] = 1;
                $result = resolve(ConstellationDetailService::class)->addData($data);
                if ($result['result']) {
                    $this->info('成功');
                } else {
                    $this->error($result['error']['message']);
                }

                $data['name'] = $contents[4];
                $data['contents'] = $contents[5];
                $data['type'] = 2;
                $result = resolve(ConstellationDetailService::class)->addData($data);
                if ($result['result']) {
                    $this->info('成功');
                } else {
                    $this->error($result['error']['message']);
                }

                $data['name'] = $contents[6];
                $data['contents'] = $contents[7];
                $data['type'] = 3;
                $result = resolve(ConstellationDetailService::class)->addData($data);
                if ($result['result']) {
                    $this->info('成功');
                } else {
                    $this->error($result['error']['message']);
                }

                $this->countedAndCheckEnded();
            },
            'rejected' => function ($reason, $index) {
                $this->error("rejected");
                $this->error("rejected reason: " . $reason);
                $this->countedAndCheckEnded();
            },
        ]);

        // 开始发送请求
        $promise = $pool->promise();
        $promise->wait();

        return true;
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->constellationCount) {
            $this->counter++;
            return;
        }
        $this->info("请求结束！");
    }
}