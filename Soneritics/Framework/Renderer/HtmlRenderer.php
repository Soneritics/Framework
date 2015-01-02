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
namespace Framework\Renderer;

use Framework\Helpers\Form;

/**
 * 
 * 
 * @author Jordi Jolink
 * @since 9-12-2014
 */
class HtmlRenderer extends Renderer
{
    protected $form;

    protected function escape($string)
    {
        $string = (string)$string;

        if (!mb_detect_encoding($string, 'UTF-8', true)) {
            $string = utf8_encode($string);
        }

        return htmlspecialchars($string);
    }

    private function getAbsoluteViewFileUrl($viewFile)
    {
        if (file_exists($viewFile . '.php')) {
            return $viewFile . '.php';
        }

        return $this->modulePath . '/' . $viewFile . '.php';
    }

    public function render($viewFile, array $params, $layout = null)
    {
        $this->form = new Form();
        extract($params);

        ob_start();
        include($this->getAbsoluteViewFileUrl($viewFile));
        $content = ob_get_clean();

        if ($layout !== null) {
			ob_start();
            include(
                \Application::getFolders()->get('layouts') . '/' . $layout . '.php'
            );
			return ob_get_clean();
        } else {
            return $content;
        }
    }
}
