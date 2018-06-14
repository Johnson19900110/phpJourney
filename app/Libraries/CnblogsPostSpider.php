<?php
namespace App\Libraries;

use App\Post;
use Goutte\CLient;
use Symfony\Component\DomCrawler\Crawler;

class CnblogsPostSpider {

    protected $client;

    protected $crawler;

    protected $urls = [];

    public function __construct(Client $client, $url)
    {
        $this->client = $client;
        $this->crawler = $client->request('GET', $url);
    }

    public function getUrls()
    {
        $urls = $this->crawler->filter('.postTitle > a')->each(function ($node) {
            return $node->attr('href');
        });

        foreach ($urls as $url) {
            $crawler = $this->client->request('GET', $url);

            $cnBlogId = $this->getCnBlogId($url);

            $post = new Post();
            if($post->where('cnblogs_id', $cnBlogId)->count()) {
                // 已爬过该博客，只更新阅读和评论数
                $post->where('cnblogs_id', $cnBlogId)->update([
                    'views'         => $this->getViews($crawler),
                    'comments'      => $this->getComments($crawler),
                ]);
            }else {
                $post->insert([
                    'title'         => $this->getTitle($crawler),
                    'category_id'   => 1,
                    'content'       => $this->getContent($crawler),
                    'user_id'       => 1,
                    'views'         => $this->getViews($crawler),
                    'comments'      => $this->getComments($crawler),
                    'cnblogs_id'    => $cnBlogId,
                    'cnblogs_url'   => $url,
                    'created_at'    => $this->getCreatedAt($crawler),
                ]);
            }
        }
    }

    public function getCnBlogId($url)
    {
        $url_arr = explode('/', $url);
        $last = array_pop($url_arr);
        $path_arr = explode('.', $last);
        return intval(array_shift($path_arr));
    }

    protected function getTitle(Crawler $crawler)
    {
        return trim($crawler->filter('.postTitle > a')->text());
    }

    protected function getContent(Crawler $crawler)
    {
        return trim($crawler->filter('#cnblogs_post_body')->text());
    }

    protected function getViews(Crawler $crawler)
    {
        return intval(trim($crawler->filter('#post_view_count')->text()));
    }

    protected function getComments(Crawler $crawler)
    {
        return intval($crawler->filter('#post_comment_count')->text());
    }

    protected function getCreatedAt(Crawler $crawler)
    {
        return trim($crawler->filter('#post-date')->text());
    }
}