<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte;

use Symfony\Component\Process\Process;

use Symfony\Component\Process\Exception\ProcessFailedException;

// include "simple_html_dom.php";

class crawlOne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:one';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $url = 'https://vnexpress.net/thoi-su/tau-khach-huc-vang-xe-tai-tai-xe-tu-vong-3912315.html';
        $html = file_get_html($url);
        $element = $html->find('.day_time');
        if ($element == false) {
            $crawler = Goutte::request('GET', $url);

            $time = $crawler->filter('span.time.left')->each(function ($node) {
                return $node->text();
            });
            if ($time == null) {
                $time = $crawler->filter('span.block_timer.left.txt_666')->each(function ($node) {
                    return $node->text();
                });
            }

            $title = $crawler->filter('h1.title_news_detail.mb10')->each(function ($node) {
                return $node->text();
            });
            if ($title == null) {
                $title = $crawler->filter('div.title_news')->each(function ($node) {
                    return $node->text();
                });
            }

            $description = $crawler->filter('p.description')->each(function ($node) {
                return $node->text();
            });
            if ($description == null) {
                $description = $crawler->filter('h2.short_intro.txt_666')->each(function ($node) {
                    return $node->text();
                });
            }
            // $description = str_replace('Dân trí','',$description[0]);
            $content = $crawler->filter('article.content_detail.fck_detail.width_common.block_ads_connect')->each(function ($node) {
                return $node->text();
            });
            if ($content == null) {
                $content = $crawler->filter('article.content_detail.fck_detail.width_common')->each(function ($node) {
                        return $node->text();
                });
            }
            if ($content == null) {
                $content = $crawler->filter('div.fck_detail.width_common.block_ads_connect')->each(function ($node) {
                        return $node->text();
                });
            }

            // $time = trim($time);
            preg_match('(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})', $time[0], $day);
            $timeInit = $day[0]."\n";

            $title = trim($title[0]);
            $title = $title."\n";

            $description = trim($description[0]);
            $description = preg_replace('([\s]+)', ' ', $description);

            $content = trim($content[0]);
            $content = preg_replace('([\s]+)', ' ', $content);

            $url = $url."\n";

            $f2 = @fopen('content.txt','w');
            fwrite($f2,$url);
            fwrite($f2,$timeInit);
            fwrite($f2,$title);
            fwrite($f2,$description);
            fwrite($f2,$content);
            fclose($f2);

            // print("Title: ".$title);

            $process = new Process("python3 ~/goutte/time6.py");
            $process->run();
            echo($process->getOutput());
        }
        
    }
}
