<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte;

use Symfony\Component\Process\Process;

use Symfony\Component\Process\Exception\ProcessFailedException;

include_once "simple_html_dom.php";

class crawlVNN extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:VNN';

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
        $url = 'https://vietnamnet.vn/vn/thoi-su/an-toan-giao-thong/';
        $scrape = Goutte::request('GET',$url);
        self::crawlPageVNN($scrape);

        $page = 2;
        $pageString = (string)$page;
        $url = env('VNN').$pageString."/";

        $n = 1;
        while ($n < 46) {
            // n = 46
            self::crawlPageVNN(Goutte::request('GET',$url));
            $page++;
            $pageString = (string)$page;
            $url = env('VNN').$pageString."/";
            $n++;
        }
    }

    public static function crawlPageVNN($crawler)
    {
        $linkNewspapers = $crawler->filter('a.f-18.title')->each(function ($node) {
                return $node->attr('href');
        });
        foreach ($linkNewspapers as $linkNewspaper) {
            self::crawlVNN(env('VNNurl').$linkNewspaper);
        }

        // $next_page = $crawler->filter('a.fon27.mt1.mr2')->each(function ($node) {
        //     return $node->attr('href');
        // })[0];
    }


    public static function crawlVNN($url)
    {   
        ini_set('max_execution_time',300);
        $html = file_get_html($url);
        $element1 = $html->find('.ArticleDate.right');
        if ($element1 != false) {
            $crawler = Goutte::request('GET', $url);

            $time = $crawler->filter('span.ArticleDate.right')->each(function ($node) {
                return $node->text();
            })[0];

            $title = $crawler->filter('h1.title.f-22.c-3e')->each(function ($node) {
                return $node->text();
            })[0];

            $description = $crawler->filter('div#ArticleContent')->filter('p')->each(function ($node) {
                    return $node->text();
            })[0];

            $content = $crawler->filter('div#ArticleContent')->each(function ($node) {
                return $node->text();
            })[0];

            $time = trim($time);
            preg_match('(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})', $time, $day);
            $timeInit = $day[0]."\n";

            $title = trim($title);
            $title = $title."\n";
        
            $description = trim($description);
            $description = preg_replace('([\s]+)', ' ', $description);

            $content = trim($content);
            $content = preg_replace('([\s]+)', ' ', $content);
            $content = str_replace($description,' ',$content);
            
            $element2 = $html->find('.article-relate');
            if ($element2 != false) {
                $relate = $crawler->filter('div.article-relate')->each(function ($node) {
                    return $node->text();
                })[0];
                $relate = trim($relate);
                $relate = preg_replace('([\s]+)', ' ', $relate);
                $content = str_replace($relate,' ',$content);
            }

            $url = $url."\n";

            $f2 = @fopen('content.txt','w');
            fwrite($f2,$url);
            fwrite($f2,$timeInit);
            fwrite($f2,$title);
            fwrite($f2,$description);
            fwrite($f2,$content);
            fclose($f2);

           
            print("Title: ".$title);
            // print("Time: ".$timeInit);
            // // print("Relate: ".$relate."\n");
            // print("description: ".$description."\n");
            // print("Content: ".$content."\n");
            // print("-----------------------"."\n");



            $process = new Process("python3 ~/goutte2/time6.py");
            $process->run();
            echo($process->getOutput());
        }
        
    }
}
