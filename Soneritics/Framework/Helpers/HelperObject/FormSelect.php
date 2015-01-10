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

/**
 * Select HTML element.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  10-1-2015
 */
class FormSelect extends AbstractHelperObject
{
    private $options = [];
    private $includeEmpty = false;

    /**
     * Add a single option to a select object.
     *
     * @param type $id
     * @param type $value
     * @return \Framework\Helpers\HelperObject\FormSelect
     */
    public function addOption($id, $value)
    {
        $this->options[$id] = $value;
        return $this;
    }

    /**
     * Add an array of options to the select.
     *
     * @param array $options
     * @return \Framework\Helpers\HelperObject\FormSelect
     */
    public function addOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->addOption($key, $value);
        }

        return $this;
    }

    /**
     * Indicator whether to include an empty value as the first element.
     *
     * @param type $includeEmpty
     * @return \Framework\Helpers\HelperObject\FormSelect
     */
    public function includeEmpty($includeEmpty = true)
    {
        $this->includeEmpty = $includeEmpty;
        return $this;
    }

    /**
     * Get the HTML option with the  correct value, caption and selected state.
     *
     * @param string $key
     * @param string $value
     * @param string $selected
     * @return string
     */
    private function getHtmlOption($key, $value, $selected = '')
    {
        return sprintf(
            '<option value=%s%s>%s</option>',
            $this->formatValue($key),
            $selected == $value ? ' selected="selected"' : '',
            htmlspecialchars($value)
        );
    }

    /**
     * Render the object.
     *
     * @return string HTML code of the object.
     */
    public function render()
    {
        $optionsHtml = [];

        if ($this->includeEmpty) {
            $optionsHtml[] = $this->getHtmlOption('', '');
        }

        if (isset($this->params['value'])) {
            $selected = $this->params['value'];
            unset($this->params['value']);
        } else {
            $selected = '';
        }

        foreach ($this->options as $key => $value) {
            $optionsHtml[] = $this->getHtmlOption($key, $value, $selected);
        }

        $opts = implode('', $optionsHtml);
        echo $this->renderHTML("<select%s>{$opts}</select>");
    }
}
