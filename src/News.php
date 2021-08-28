<?php

namespace Azeroglu\News;

use Azeroglu\News\Services\BotService;

class News extends BotService
{
    /*
     * Oxu Az
     * */
    public function oxuAz($limit = 5)
    {
        $links = $this
            ->request('https://oxu.az/')
            ->links('.news-list .news-i .news-i-inner', 'https://oxu.az')
            ->limit($limit)
            ->get();
        $items = [];
        foreach ($links as $link):
            try {
                $dom      = $this->dom($link);
                $dateHtml = $this->date($dom, '.news .when');
                $dateHtml = $this->domParser()->load($dateHtml);
                $day      = trim($dateHtml->find('.when-date .date-day')->text, '&nbsp');
                $month = $dateHtml->find('.when-date .date-month')->text;
                $year = $dateHtml->find('.when-date .date-year')->text;
                $time = $dateHtml->find('.when-time')->text;
                $items[] = [
                    'link' => $link,
                    'title'   => $this->title($dom, '.news .news-inner h1'),
                    'photo'   => $this->photo($dom, '.news .news-img'),
                    'content' => preg_replace('/<h1>(.*)<\/h1>/', null, $this->content($dom, '.news .news-inner')),
                    'date' => $day . '-' . $month . '-' . $year . ' ' . $time,
                    'category' => $this->category($dom, '.nav-container .nav-i.nav-i_current')
                ];
            }
            catch (\Exception $e) {

            }
        endforeach;
        return $items;
    }

    /*
     * Report Az
     * */
    public function reportAz($limit = 1)
    {
        $links = $this
            ->request('https://report.az/')
            ->links('.latest-news .news-item .info a.title')
            ->limit($limit)
            ->get();

        return $links;
    }
}
