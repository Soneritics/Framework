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
namespace Framework\IO;

/**
 * File object. Provides functions for files.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  23-3-2015
 */
class File
{
    /**
     *Path of the file.
     * @var string
     */
    private $path;

    /**
     * Filename without the path.
     * @var string
     */
    private $filename;

    /**
     * Create the file object.
     * @param string $filename
     */
    public function __construct($filename = null)
    {
        if ($filename !== null) {
            $this->load($filename);
        }
    }

    /**
     * Load a file.
     * @param string $filename
     * @return $this
     */
    public function load($filename)
    {
        $this->path = dirname($filename) . DIRECTORY_SEPARATOR;
        $this->filename = basename($filename);
        return $this;
    }

    /**
     * Get the path of the File.
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the base name of the file.
     * @return type
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the path of the file.
     * @param type $path
     * @return $this
     */
    public function setPath($path)
    {
        if (substr($path, -1) !== DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }

        $this->path = $path;
        return $this;
    }

    /**
     * Set the base name of the file.
     * @param string $filename
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Indicator whether the file exists.
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->getPath() . $this->getFilename());
    }
}
