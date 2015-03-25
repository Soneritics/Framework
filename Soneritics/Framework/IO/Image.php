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
 * Image object. Provides functions for images.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  23-3-2015
 */
class Image extends File
{
    // Constants for reading the $imageInfo properties.
    CONST WIDTH = 0;
    CONST HEIGHT = 1;
    CONST BITS = 'bits';
    CONST MIME = 'mime';

    /**
     * Array as given from PHP's getimageinfo function.
     * @var array
     */
    private $imageInfo;

    /**
     * Read the info from an image.
     * @throws \Exception
     */
    private function readImageInfo()
    {
        if ($this->imageInfo === null) {
            $filename = $this->getPath() . $this->getFilename();
            if (!$this->exists()) {
                throw new \Exception("File $filename does not exist.");
            }

            $this->imageInfo = getimagesize($filename);

            if ($this->imageInfo === false) {
                throw new \Exception("Invalid image: $filename");
            }
        }
    }

    /**
     * Get an image's width in pixels.
     * @return int
     */
    public function getWidth()
    {
        $this->readImageInfo();
        return $this->imageInfo[self::WIDTH];
    }

    /**
     * Get an image's height in pixels.
     * @return int
     */
    public function getHeight()
    {
        $this->readImageInfo();
        return $this->imageInfo[self::HEIGHT];
    }

    /**
     * Return the amount of bits in an image.
     * @return int
     */
    public function getBits()
    {
        $this->readImageInfo();
        return $this->imageInfo[self::BITS];
    }

    /**
     * Get the image's mime type.
     * @return string
     */
    public function getMime()
    {
        $this->readImageInfo();
        return $this->imageInfo[self::MIME];
    }
}