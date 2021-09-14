<?php

namespace Azeroglu\News;

use Azeroglu\Bot\Bot;

class News extends Bot
{
    /*
     * Convert Date
     * */
    protected function convertDate($date)
    {
        $e = explode(' ', $date);
        $month   = isset($e[1]) ? mb_convert_case($e[1], MB_CASE_LOWER, 'UTF-8') : null;
        if ($month === 'yan' || $month === 'yanvar'):
            $month = '01';
        elseif ($month === 'fev' || $month === 'fevral'):
            $month = '02';
        elseif ($month === 'mar' || $month === 'mart'):
            $month = '03';
        elseif ($month === 'apr' || $month === 'aprel'):
            $month = '04';
        elseif ($month === 'may'):
            $month = '05';
        elseif ($month === 'iyun'):
            $month = '06';
        elseif ($month === 'iyul'):
            $month = '07';
        elseif ($month === 'avq' || $month === 'avqust'):
            $month = '08';
        elseif ($month === 'sen' || $month === 'sentyabr'):
            $month = '09';
        elseif ($month === 'okt' || $month === 'oktyabr'):
            $month = '10';
        elseif ($month === 'noy' || $month === 'noyabr'):
            $month = '11';
        elseif ($month === 'dek' || $month === 'dekabr'):
            $month = '12';
        endif;
        return $month ? $e[2] . '-' . $month . '-' . $e[0] . ' ' . (isset($e[3]) ? $e[3] . ':00' : '00:00:00') : $date;
    }

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
            $date     = $day . ' ' . $month . ' ' . $year . ' ' . $time;
            return $this->newsResponse(
                $this->text($dom, '.news .news-inner h1'),
                $this->text($dom, '.nav-container .nav-i.nav-i_current'),
                preg_replace('/<h1>(.*)<\/h1>/', null, $this->innerHtml($dom, '.news .news-inner')),
                $this->src($dom, '.news .news-img'),
                $this->convertDate($date),
                $link,
                'az'
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
                $this->convertDate($this->text($dom, '.quiz-holder .date-info')),
                $link,
                'az'
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
                $this->convertDate(trim($date)),
                $link,
                'az'
            );
        });
    }

    /*
     * Son Xeber Az
     * */
    public function sonXeberAz($limit = 10)
    {
        $links = $this
            ->request('https://sonxeber.az/')
            ->links('.blk .blk_as a', 'https://sonxeber.az')
            ->limit($limit)
            ->get();
        return $this->each($links, function ($dom, $link) {
            $dateHtml = $this->text($dom, '.datespan .right');
            $date     = explode('Tarix:', $dateHtml);
            $date     = end($date);
            $photo    = $this->src($dom, '.centerblok article .imgbcode');
            $photo    = $photo ? 'https://sonxeber.az' . $photo : null;
            return $this->newsResponse(
                $this->text($dom, '.centerblok article h1'),
                $this->text($dom, '.datespan .right a'),
                preg_replace(['/<h1>(.*)<\/h1>/', '/<img.*class=("|"([^"]*)\s)imgbcode("|\s([^"]*)").*?\/>/'], null, $this->innerHtml($dom, '.centerblok article')),
                $photo,
                $this->convertDate(trim($date)),
                $link,
                'az'
            );
        });
    }

    /*
     * Baku Ws
     * */
    public function bakuWs($limit = 10)
    {
        $links = $this
            ->request('https://baku.ws/')
            ->links('#dle-content .post .post-image a')
            ->limit($limit)
            ->get();
        return $this->each($links, function ($dom, $link) {
            $dateHtml = $this->innerHtml($dom, '.published-box');
            $dateHtml = $this->dom($dateHtml);
            $day      = $dateHtml->find('.date .day')->text;
            $month    = $dateHtml->find('.date .month')->text;
            $time     = $dateHtml->find('.slider-time')->text;
            $date     = $day . ' ' . $month . ' ' . date('Y') . ' ' . trim($time);
            return $this->newsResponse(
                $this->text($dom, '.page-title h1'),
                '',
                $this->innerHtml($dom, '.post-content'),
                $this->src($dom, 'img.img-fluid', 'https://baku.ws'),
                $this->convertDate(trim($date)),
                $link,
                'az'
            );
        });
    }

    /*
     * Maraqli Tv
     * */
    public function maraqliTv($limit = 1)
    {
        $links = $this
            ->request('https://maraqli.tv/')
            ->links('.g1-collection-items .g1-collection-item article .g1-frame')
            ->limit($limit)
            ->get();
        return $this->each($links, function ($dom, $link) {
            $photoHtml = $this->innerHtml($dom, '.entry-inner .entry-featured-media');
            $photo     = $this->dom($photoHtml)->find('img', 0)->getAttribute('data-src');
            $date      = str_replace([','], null, $this->text($dom, '.entry-header .entry-meta .entry-date'));
            return $this->newsResponse(
                $this->text($dom, '.entry-inner .entry-header .entry-title'),
                $this->text($dom, '.entry-inner .entry-before-title .entry-category span'),
                $this->innerHtml($dom, '.entry-inner .entry-content'),
                $photo,
                $this->convertDate($date),
                $link,
                'az'
            );
        });
    }
}
