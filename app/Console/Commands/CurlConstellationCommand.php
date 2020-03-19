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

        $this->constellationCount = count($this->linkList);

        $client = new Client();

        $requests = function ($total) use ($client) {
            foreach ($this->linkList as $key => $link) {
                yield function () use ($client, $link) {
                    return $client->getAsync($link);
                };
            }
        };

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

                $contentsCount = count($contents);
                $key = 0;
                for($i=0; $i<$contentsCount; $i+=2) {
                    $data['name'] = $contents[0+$i];
                    $data['contents'] = $contents[1+$i];
                    $data['type'] = $key;

                    $queryData = [];
                    $queryData['constellation_id'] = $data['constellation_id'];
                    $queryData['type'] = $data['type'];
                    $queryData['date'] = $data['date'];
                    $result = resolve(ConstellationDetailService::class)->getIdByDateAndType($queryData);

                    if ($result['result']) {
                        $data['id'] = $result['data']['id'];
                        $result = resolve(ConstellationDetailService::class)->updateData($data);
                    } else {
                        $result = resolve(ConstellationDetailService::class)->addData($data);
                    }
                    $key++;
                    if ($result['result']) {
                        $this->info($this->starSignList[$index].'-'.$data['name'].'成功');
                    } else {
                        $this->error($result['error']['message']);
                    }
                }

                $this->countedAndCheckEnded();
            },
            'rejected' => function ($reason, $index) {
                $this->error("rejected");
                $this->error("rejected reason: " . $reason);
                $this->countedAndCheckEnded();
            },
        ]);

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