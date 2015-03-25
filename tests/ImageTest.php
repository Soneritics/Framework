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

use Framework\IO\Image;

/**
 * Test the Framework\IO\Image class.
 *
 * @author Jordi Jolink
 * @since 27-3-2015
 */
class ImageTest extends FrameworkTest
{
    /**
     * Array with assets to perform the tests on with their widths/heights.
     * @var array
     */
    private $assets = [
        'png' => [60, 60],
        'ico' => [48, 48]
    ];

    /**
     * Get the full path of an image asset.
     * @param string $type Extension of the asset.
     * @return string
     */
    private function getAsset($type)
    {
        return __DIR__ . "/assets/ImageTest/image.{$type}";
    }

    /**
     * Test if the assets exist.
     */
    public function testAssetsExist()
    {
        foreach (array_keys($this->assets) as $asset) {
            $image = new Image($this->getAsset($asset));
            $this->assertTrue($image->exists());
        }
    }

    /**
     * Test getting the width and height of the assets.
     */
    public function testGetWidthHeight()
    {
        foreach ($this->assets as $asset => $widthHeight) {
            $image = new Image($this->getAsset($asset));
            $this->assertEquals($widthHeight[0], $image->getWidth());
            $this->assertEquals($widthHeight[1], $image->getHeight());
        }
    }

    /**
     * Test for an exception when trying to process an invalid image.
     * @expectedException Exception
     */
    public function testErroneousImage()
    {
        $notAnImage = new Image($this->getAsset('err'));
        $this->assertEquals(0, $notAnImage->getWidth());
    }

    /**
     * Test mime type for the image.
     */
    public function testMime()
    {
        $image = new Image($this->getAsset('png'));
        $this->assertEquals('image/png', $image->getMime());
    }

    /**
     * Test for the amount of bits in the image.
     */
    public function testBits()
    {
        $image = new Image($this->getAsset('png'));
        $this->assertEquals(8, $image->getBits());
    }
}
