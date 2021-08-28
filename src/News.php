<?php

namespace Azeroglu\News;

use Azeroglu\News\Services\BotService;

class News extends BotService
{
    /*
     * Oxu Az
     * */
    public function oxuAz($limit = 10)
    {
        $links = $this
            ->request('https://oxu.az/')
            ->links('.news-list .news-i .news-i-inner', 'https://oxu.az')
            ->limit($limit)
            ->get();
        return $this->each($links, function ($dom, $link) {
            $dateHtml = $this->innerHtml($dom, '.news .when');
            $dateHtml = $this->domParser()->load($dateHtml);
            $day      = trim($dateHtml->find('.when-date .date-day')->text, '&nbsp');
            $month    = $dateHtml->find('.when-date .date-month')->text;
            $year     = $dateHtml->find('.when-date .date-year')->text;
            $time     = $dateHtml->find('.when-time')->text;
            $date     = $day . '-' . $month . '-' . $year . ' ' . $time;
            return $this->newsResponse(
                $this->text($dom, '.news .news-inner h1'),
                $this->text($dom, '.nav-container .nav-i.nav-i_current'),
                preg_replace('/<h1>(.*)<\/h1>/', null, $this->innerHtml($dom, '.news .news-inner')),
                $this->src($dom, '.news .news-img'),
                $date,
                $link
            );
        });
    }

    /*
     * Milli Az
     * */
    public function milliAz($limit = 10)
    {
        $links = $this
            ->request('https://www.milli.az/')
            ->links('.post-list2 li > a')
            ->limit($limit)
            ->get();
        return $this->each($links, function ($dom, $link) {
            return $this->newsResponse(
                $this->text($dom, '.quiz-holder h1'),
                $this->text($dom, '.quiz-holder .category'),
                $this->innerHtml($dom, '.quiz-holder .article_text'),
                $this->src($dom, '.quiz-holder .content-img'),
                $this->text($dom, '.quiz-holder .date-info'),
                $link,
            );
        });
    }

    /*
     * Apa Az
     * */
    public function apaAz($limit = 10)
    {
        $links = $this
            ->request('https://apa.az/az')
            ->links('.news_block .news .item')
            ->limit($limit)
            ->get();
        return $this->each($links, function ($dom, $link) {
            $dateHtml    = $this->text($dom, '.news_main .content_main .date_news .date span.date');
            $dateExplode = explode('(', $dateHtml);
            $date        = isset($dateExplode[0]) ? $dateExplode[0] : $dateHtml;
            return $this->newsResponse(
                $this->text($dom, '.news_main .title_news'),
                $this->text($dom, '.container .breadcrumb_row h3'),
                $this->innerHtml($dom, '.news_content .texts'),
                $this->src($dom, '.news_main .content_main .main_img img'),
                trim($date),
                $link
            );
        });
    }
}
