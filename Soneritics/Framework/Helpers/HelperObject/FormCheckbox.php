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
namespace Framework\Helpers\HelperObject;

use Framework\Web\Request\RequestAbstract;

/**
 * Input type=checkbox element.
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  16-2-2015
 */
class FormCheckbox extends AbstractHelperObject
{
    private $renderFormat = '<input type="checkbox"%s>';

    /**
     * Set the rendering format.
     * @param string $renderFormat
     * @return \Framework\Helpers\HelperObject\FormText
     */
    public function setRenderFormat($renderFormat)
    {
        $this->renderFormat = $renderFormat;
        return $this;
    }

    /**
     * Render the object.
     * @return string HTML code of the object.
     */
    public function render()
    {
        if ($this->getParam('value') === null) {
            $this->setValue('on');
        }

        echo $this->renderHTML($this->renderFormat);
    }

    /**
     * Set the value for this object from a previously submitted form.
     * @param RequestAbstract $request
     */
    protected function setValueFrom(RequestAbstract $request)
    {
        if ($request->get($this->getName()) !== null) {
            $this->setParam('checked', 'checked');
        }
        $this->setValue($request->get($this->getName()));
    }
}
