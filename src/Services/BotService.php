<?php

namespace Azeroglu\News\Services;

use PHPHtmlParser\Dom;

class BotService
{
    protected $dom;
    protected $html;
    protected $links = [];
    protected $page;

    protected function __construct()
    {
        $this->dom = $this->domParser();
    }

    /*
     * Dom Parser
     * */
    protected function domParser()
    {
        return new Dom();
    }

    /*
     * Request
     * */
    protected function request($url)
    {
        try {
            $this->html = $this->dom->load($url);
            return $this;
        }
        catch (\Exception $e) {
            return $e;
        }
    }

    /*
     * Links
     * */
    protected function links($element, $prefix = null)
    {
        try {
            $links  = $this->html->find($element);
            $result = [];
            foreach ($links as $link):
                $result[] = $prefix ? $prefix . $link->href : $link->href;
            endforeach;
            $this->links = $result;
            return $this;
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /*
     * Limit
     * */
    protected function limit($limit)
    {
        $this->links = array_splice($this->links, 0, $limit);
        return $this;
    }

    /*
     * Get
     * */
    protected function get()
    {
        return $this->links;
    }

    /*
     * Dom
     * */
    protected function dom($link)
    {
        try {
            return $this->dom->load($link);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /*
     * Title
     * */
    protected function title($dom, $element)
    {
        try {
            return $dom->find($element)->text;
        }
        catch (\Exception $e) {
            return null;
        }
    }

    /*
     * Photo
     * */
    protected function photo($dom, $element)
    {
        try {
            return $dom->find($element)->src;
        }
        catch (\Exception $e) {
            return null;
        }
    }

    /*
     * Content
     * */
    protected function content($dom, $element)
    {
        try {
            return $dom->find($element)->innerHtml;
        }
        catch (\Exception $e) {
            return null;
        }
    }

    /*
     * Date
     * */
    protected function date($dom, $element)
    {
        try {
            return $dom->find($element)->innerHtml;
        }
        catch (\Exception $e) {
            return null;
        }
    }

    /*
     * Category
     * */
    protected function category($dom, $element)
    {
        try {
            return $dom->find($element)->text;
        }
        catch (\Exception $e) {
            return null;
        }
    }
}
