<?php

namespace Azeroglu\News\Services;

use PHPHtmlParser\Dom;

class BotService
{
    protected $dom;
    protected $html;
    protected $links = [];
    protected $page;

    public function __construct()
    {
        $this->dom = $this->domParser();
    }

    /*
     * Dom Parser
     * */
    public function domParser()
    {
        return new Dom();
    }

    /*
     * Request
     * */
    public function request($url)
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
    public function links($element, $prefix = null)
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
    public function limit($limit)
    {
        $this->links = array_splice($this->links, 0, $limit);
        return $this;
    }

    /*
     * Get
     * */
    public function get()
    {
        return $this->links;
    }

    /*
     * Dom
     * */
    public function dom($link)
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
    public function title($dom, $element)
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
    public function photo($dom, $element)
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
    public function content($dom, $element)
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
    public function date($dom, $element)
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
    public function category($dom, $element)
    {
        try {
            return $dom->find($element)->text;
        }
        catch (\Exception $e) {
            return null;
        }
    }
}
