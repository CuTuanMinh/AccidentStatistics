<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte;

use Symfony\Component\Process\Process;

use Symfony\Component\Process\Exception\ProcessFailedException;

class crawlThanhnien extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:TN';

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
        $url = 'https://thanhnien.vn/doi-song/giao-thong/';
        $scrape = Goutte::request('GET',$url);
        self::crawlPageTN($scrape);

        $page = 2;
        $pageString = (string)$page;
        $url = env('Thanhnien').$pageString.".html";

        $n = 1;
        while ($n < 2) {
            // n = 32
            // $url = env('DAN_TRI').$next_page;
            self::crawlPageTN(Goutte::request('GET',$url));
            $page++;
            $pageString = (string)$page;
            $url = env('Thanhnien').$pageString.".html";
            $n++;
        }
    }

    public static function crawlPageTN($crawler)
    {
        $linkNewspapers = $crawler->filter('a.story__title')->each(function ($node) {
                return $node->attr('href');
        });
        foreach ($linkNewspapers as $linkNewspaper) {
            self::crawlTN(env('ThanhnienURL').$linkNewspaper);
        }

        // $next_page = $crawler->filter('a.fon27.mt1.mr2')->each(function ($node) {
        //     return $node->attr('href');
        // })[0];
    }


    public static function crawlTN($url)
    {
        $crawler = Goutte::request('GET', $url);

        $time = $crawler->filter('div.meta')->filter('time')->each(function ($node) {
            return $node->text();
        })[0];

        $title = $crawler->filter('h1.details__headline')->each(function ($node) {
            return $node->text();
        })[0];

        $description = $crawler->filter('div#chapeau')->each(function ($node) {
            return $node->text();
        })[0];

        $content = $crawler->filter('div#abody')->each(function ($node) {
            return $node->text();
        })[0];

        $time = trim($time);
        preg_match('(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})', $time, $day);
        $timeInit = $day[0]."\n";

        $title = trim($title);
        $title = $title."\n";
    
        $description = trim($description);
        $description = preg_replace('([\s]+)', ' ', $description);
        $description = $description."\n";

        $content = trim($content);
        $content = preg_replace('([\s]+)', ' ', $content);

        $url = $url."\n";

        // $f2 = @fopen('content.txt','w');
        // fwrite($f2,$url);
        // fwrite($f2,$timeInit);
        // fwrite($f2,$title);
        // fwrite($f2,$description);
        // fwrite($f2,$content);
        // fclose($f2);

       
        print("Title: ".$title);
        // print("Time: ".$timeInit);
        // print("Description: ".$description);
        // print("Content: ".$content."\n");


        // $process = new Process("python3 ~/goutte/time6.py");
        // $process->run();
        // echo($process->getOutput());
    }
}
