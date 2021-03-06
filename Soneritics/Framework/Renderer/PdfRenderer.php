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

/**
 * Render a PDF document.
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since 18-3-2015
 */
class PdfRenderer extends Renderer
{
    protected $pdf;

    protected function escape($string)
    {
        $string = (string)$string;

        if (!mb_detect_encoding($string, 'UTF-8', true)) {
            $string = utf8_encode($string);
        }

        return $string;
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
        extract($params);

        if ($layout !== null) {
            include \Application::getFolders()->get('layouts') . "/{$layout}.php";
        }

        $this->pdf = new \fpdf\FPDF;
        include $this->getAbsoluteViewFileUrl($viewFile);
        return $this->pdf->Output('', 'S');
    }
}
