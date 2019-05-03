<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte;

use Symfony\Component\Process\Process;

use Symfony\Component\Process\Exception\ProcessFailedException;

class crawlBao3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:bao3';

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
        $url = 'https://laodong.vn/giao-thong';
        $scrape = Goutte::request('GET',$url);
        self::crawlPageLaodong($scrape);

        $page = 2;
        $pageString = (string)$page;
        $url = env('Laodong').$pageString;

        $n = 1;
        while ($n < 32) {
            // n = 32
            self::crawlPageLaodong(Goutte::request('GET',$url));
            $page++;
            $pageString = (string)$page;
            $url = env('Laodong').$pageString;
            $n++;
        }
    }

    public static function crawlPageLaodong($crawler)
    {
        $linkNewspapers = $crawler->filter('article.article-large.N2')->filter('h4')->filter('a')->each(function ($node) {
                return $node->attr('href');
        });
        foreach ($linkNewspapers as $linkNewspaper) {
            self::crawlLaodong($linkNewspaper);
        }

        // $next_page = $crawler->filter('a.fon27.mt1.mr2')->each(function ($node) {
        //     return $node->attr('href');
        // })[0];
    }


    public static function crawlLaodong($url)
    {
        $crawler = Goutte::request('GET', $url);

        $time = $crawler->filter('time.f-datetime')->each(function ($node) {
            return $node->text();
        })[0];

        $title = $crawler->filter('div.title')->filter('h1')->each(function ($node) {
            return $node->text();
        })[0];

        $description = $crawler->filter('p.abs')->each(function ($node) {
            return $node->text();
        })[0];

        $content = $crawler->filter('div.article-content')->each(function ($node) {
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

        $url = $url."\n";

        $f2 = @fopen('content.txt','w');
        fwrite($f2,$url);
        fwrite($f2,$timeInit);
        fwrite($f2,$title);
        fwrite($f2,$description);
        fwrite($f2,$content);
        fclose($f2);

       
        print("Title: ".$title);


        $process = new Process("python3 ~/goutte2/time6.py");
        $process->run();
        echo($process->getOutput());
    }
}
