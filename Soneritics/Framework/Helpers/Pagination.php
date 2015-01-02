<?php
/* 
 * The MIT License
 *
 * Copyright 2014 Soneritics Webdevelopment.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Framework\Helpers;

use Framework\MVC\View;

/**
 * Pagination helper.
 * 
 * @author Jordi Jolink
 * @date 26-12-2014
 */
class Pagination
{
    private $page = 1,
            $pages = 1,
            $url = '?page=%s';

    /**
     * Setter for the currently active page.
     * 
     * @param type $page
     * @return \Framework\Helpers\Pagination
     */
    public function setCurrentPage($page)
    {
        $this->page = (int)$page;
        return $this;
    }

    /**
     * Setter for the total amount of pages.
     * 
     * @param type $pages
     * @return \Framework\Helpers\Pagination
     */
    public function setPages($pages)
    {
        $this->pages = (int)$pages;
        return $this;
    }

    /**
     * Setter for the URL.
     * 
     * @param type $url
     * @return \Framework\Helpers\Pagination
     */
    public function setURL($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Find out which page to show first, and what page last.
     * 
     * @return array($from, $to)
     */
    private function getFromTo()
    {
        if ($this->pages <= 5) {
            return array(1, $this->pages);
        }

        if ($this->page <= 3) {
            return array(1, 5);
        }

        if ($this->page + 2 >= $this->pages) {
            return array($this->pages - 4, $this->pages);
        }

        return array($this->page - 2, $this->page + 2);
    }

    /**
     * Function to get the parameters needed for the pagination view, which
     * can be directly passed into a View's setParams() function.
     * 
     * @return array
     */
    private function getViewParams()
    {
        list($from, $to) = $this->getFromTo();

        return array(
            'page' => $this->page,
            'from' => $from,
            'to' => $to,
            'prev' => $this->page > 1,
            'next' => $this->page < $this->pages,
            'url' => $this->url
        );
    }

    /**
     * Return the view that contains the pagination.
     * 
     * @return View
     */
    public function getView()
    {
        if ($this->pages < 1) {
            $this->pages = 1;
        }

        if ($this->page < 1 || $this->page > $this->pages) {
            $this->page = 1;
        }

        $view = new View(__DIR__ . '/Pagination/View');
        $view->setParams($this->getViewParams());
        return $view;
    }
}