<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
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
    public function handle()
    {
        $client = resolve(Client::class);
        $content = $client->request('GET', 'http://astro.click108.com.tw/')->getBody()->getContents();
        $crawler = resolve(Crawler::class);
        $a = $crawler->selectLink($content);
        print_r($a);
    }
}
