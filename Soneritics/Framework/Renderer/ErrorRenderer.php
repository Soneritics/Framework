<?php
/* 
 * The MIT License
 *
 * Copyright 2014 Jordi Jolink.
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

use Framework\Web\Server;

/**
 * 
 * 
 * @author Jordi Jolink
 * @date 20-12-2014
 */
class ErrorRenderer extends HtmlRenderer
{
	/**
	 * Override the constructor, as it might raise errors.
	 * 
	 * @param string $module Can be omitted for the ErrorRenderer.
	 */
	public function __construct($module){}

	private function getViewFileUrl($viewFile)
	{
		$file = 'Errors/%s.php';
		if (file_exists(($f = sprintf($file, $viewFile)))) {
			return $f;
		}

		return sprintf($file, 'error');
	}

	private function getErrorLayout()
	{
		if (file_exists('Layouts/error.php')) {
			return 'error';
		}

		return 'default';
	}

	/**
	 * 
	 * 
	 * @param type $viewFile
	 * @param array $params
	 * @param type $layout
	 * @return string
	 */
    public function render($viewFile, array $params, $layout = null)
    {
        extract($params);

        if ((new Server())->isCLI()) {
            print_r($params); // @todo: Write to stderr
        } else {
            ob_start();
            include($this->getViewFileUrl($viewFile));
            $content = ob_get_clean();

            ob_start();
            include('Layouts/' . $this->getErrorLayout() . '.php');
            return ob_get_clean();
        }
    }
}
