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
namespace Framework;

/**
 * 
 * 
 * @author Jordi Jolink
 * @date 7-12-2014
 */
class AutoLoader
{
    private $rootPaths = array();

    public function addRootPath($path)
    {
        $this->rootPaths[] = $path;
        return $this;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
        return $this;
    }

    /**
     * 
     *
     * @param string $className The name of the class to load.
     */
    public function loadClass($className)
    {
        if (!empty($this->rootPaths)) {
            foreach ($this->rootPaths as $path) {
                $file = $path . '/' . str_replace('\\', '/', $className) . '.php';
                if (file_exists($file)) {
                    require_once($file);
                    return true;
                }
            }
        }

        return false;
    }
}