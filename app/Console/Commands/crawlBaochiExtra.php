<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte;

use Symfony\Component\Process\Process;

use Symfony\Component\Process\Exception\ProcessFailedException;

include_once "simple_html_dom.php";

class crawlBaochiExtra extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:moreData';

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
        $url = 'https://dantri.com.vn/xa-hoi/giao-thong.htm';
        $scrape = Goutte::request('GET',$url);
        self::crawlPageDantri($scrape);

        $next_page = $scrape->filter('a.fon27.mt1.mr2')->each(function ($node) {
            return $node->attr('href');
        })[0];

        $n = 1;
        while ($n < 30) {
            // n = 30
            $url = env('DAN_TRI').$next_page;
            self::crawlPageDantri(Goutte::request('GET',$url));
            $next_page = Goutte::request('GET',$url)->filter('a.fon27.mt1.mr2')->each(function ($node) {
                return $node->attr('href');
            })[0];
            $n++;
        }

        $url = 'https://vnexpress.net/thoi-su/giao-thong';
        $scrape = Goutte::request('GET',$url);
        self::crawlPageVNExpress($scrape);

        $next_page = $scrape->filter('a.next')->each(function ($node) {
            return $node->attr('href');
        })[0];

        $n = 1;
        while ($n < 12) {
            // n = 12
            $url = env('VNExpress').$next_page;
            self::crawlPageVNExpress(Goutte::request('GET',$url));
            $next_page = Goutte::request('GET',$url)->filter('a.next')->each(function ($node) {
                return $node->attr('href');
            })[0];
            $n++;
        }

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


        $url = 'https://vietnamnet.vn/vn/thoi-su/an-toan-giao-thong/';
        $scrape = Goutte::request('GET',$url);
        self::crawlPageVNN($scrape);

        $page = 2;
        $pageString = (string)$page;
        $url = env('VNN').$pageString."/";

        $n = 1;
        while ($n < 45) {
            // n = 45
            self::crawlPageVNN(Goutte::request('GET',$url));
            $page++;
            $pageString = (string)$page;
            $url = env('VNN').$pageString."/";
            $n++;
        }

    }

    public static function crawlPageDantri($crawler)
    {
        $linkNewspapers = $crawler->filter('a.fon6')->each(function ($node) {
            return $node->attr('href');
        });
        foreach ($linkNewspapers as $linkNewspaper) {
            self::crawlDantri(env('DAN_TRI').$linkNewspaper);
        }

        // $next_page = $crawler->filter('a.fon27.mt1.mr2')->each(function ($node) {
        //     return $node->attr('href');
        // })[0];
    }


    public static function crawlDantri($url)
    {
        $crawler = Goutte::request('GET', $url);

        $time = $crawler->filter('span.fr.fon7.mr2.tt-capitalize')->each(function ($node) {
            return $node->text();
        })[0];

        $title = $crawler->filter('h1.fon31.mgb15')->each(function ($node) {
            return $node->text();
        })[0];

        $description = $crawler->filter('h2.fon33.mt1.sapo')->each(function ($node) {
            return $node->text();
        });
        $description = str_replace('Dân trí','',$description[0]);
        
        // $thumbnail = $crawler->filter('tr')->filter('td')->filter('img')->each(function ($node) {
        //     return $node->attr('src');
        // });

        $content = $crawler->filter('div#divNewsContent')->each(function ($node) {
            return $node->text();
        })[0];

        $time = trim($time);
        preg_match('(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})', $time, $day);
        $timeInit = $day[0]."\n";

        $title = trim($title);
        $title = $title."\n";
    
        $description = trim($description);
        $description = preg_replace('([\s]+)', ' ', $description);
        $description = preg_replace('(\>\>.+)','',$description);

        $content = trim($content);
        $content = preg_replace('([\s]+)', ' ', $content);
        $content = preg_replace('(Tag.+)','',$content);

        $url = $url."\n";
        $namepage = 'DANTRI'."\n";

        $f2 = @fopen('content.txt','w');
        fwrite($f2,$namepage);
        fwrite($f2,$url);
        fwrite($f2,$timeInit);
        fwrite($f2,$title);
        fwrite($f2,$description);
        fwrite($f2,$content);
        fclose($f2);

        // $newspaper = new Newspaper();
        // $newspaper->title = $title;
        // $newspaper->description = $description;
        // $newspaper->content = $content;
        // $newspaper->save();
        print("Title: ".$title);


        $process = new Process("python3 ~/goutte2/time6.py");
        $process->run();
        echo($process->getOutput());
    }

    public static function crawlPageVNExpress($crawler)
    {
        $linkNewspapers = $crawler->filter('h4.title_news')->filter('a')->each(function ($node) {
            return $node->attr('href');
        });
        $reallylinkNewspapers = [];
        $pattern1 = '/vnexpress\.net\/giao-duc/';
        $pattern2 = '/#box_comment/';
        foreach ($linkNewspapers as $linkNewspaper) {
            if (preg_match($pattern1,$linkNewspaper) == null && preg_match($pattern2,$linkNewspaper) == null){
                array_push($reallylinkNewspapers, $linkNewspaper);
            }
        }
        // $i = 1;
        foreach ($reallylinkNewspapers as $reallylinkNewspaper) {
            // print($i."\n");
            // print($reallylinkNewspaper."\n");
            self::crawlVNExpress($reallylinkNewspaper);
            // $i++;
        }
    }

    public static function crawlVNExpress($url)
    {
        ini_set('max_execution_time',300);
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
            $namepage = 'VNexpress'."\n";

            $f2 = @fopen('content.txt','w');
            fwrite($f2,$namepage);
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
        $namepage = 'LAODONG'."\n";

        $f2 = @fopen('content.txt','w');
        fwrite($f2,$namepage);
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
            $namepage = 'VIETNAMNET'."\n";

            $f2 = @fopen('content.txt','w');
            fwrite($f2,$namepage);
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
    
}
