<?php

namespace Azeroglu\Bot;

use PHPHtmlParser\Dom;

class Bot
{
    protected $dom;
    protected $html;
    protected $links = [];
    protected $page;

    /*
     * Dom Parser
     * */
    protected function domParser(): Dom
    {
        return new Dom();
    }

    /*
     * Request
     * */
    protected function request($url)
    {
        try {
            $this->html = $this->domParser()->load($url);
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
    protected function limit($limit): Bot
    {
        $this->links = array_splice($this->links, 0, $limit);
        return $this;
    }

    /*
     * Get
     * */
    protected function get(): array
    {
        return $this->links;
    }

    /*
     * Dom
     * */
    protected function dom($link)
    {
        try {
            return $this->domParser()->load($link);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /*
     * Text
     * */
    protected function text($dom, $element): ?string
    {
        try {
            return trim($dom->find($element)->text);
        }
        catch (\Exception $e) {
            return null;
        }
    }

    /*
     * Src
     * */
    protected function src($dom, $element, $prefix = null): ?string
    {
        try {
            $photo = trim($dom->find($element)->src);
            return $photo ? ($prefix ? $prefix . $photo : $photo) : null;
        }
        catch (\Exception $e) {
            return null;
        }
    }

    /*
     * Inner Html
     * */
    protected function innerHtml($dom, $element): ?string
    {
        try {
            return trim($dom->find($element)->innerHtml);
        }
        catch (\Exception $e) {
            return null;
        }
    }

    /*
     * Each
     * */
    protected function each($links, $callback): ?array
    {
        $items = [];
        foreach ($links as $link):
            try {
                if (is_callable($callback)):
                    $dom     = $this->dom($link);
                    $items[] = call_user_func($callback, $dom, $link);
                endif;
            }
            catch (\Exception $e) {
                return null;
            }
        endforeach;
        return $items;
    }

    /*
     * Response
     * */
    protected function newsResponse($title, $category, $content, $photo, $date, $link): array
    {
        return [
            'title'    => $title,
            'category' => $category,
            'content'  => $content,
            'photo'    => $photo,
            'date'     => $date,
            'link'     => $link,
        ];
    }
}
