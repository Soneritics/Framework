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
    const WIDTH = 0;
    const HEIGHT = 1;
    const IMAGETYPE = 2;
    const BITS = 'bits';
    const MIME = 'mime';

    const IMAGE_GIF = 1;
    const IMAGE_JPG = 2;
    const IMAGE_PNG = 3;

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

    /**
     * Get a resource handle for the currently selected image file.
     * @return resource
     */
    public function getHandle()
    {
        $this->readImageInfo();
        $filename = $this->getPath() . $this->getFilename();
        $result = null;

        switch ($this->imageInfo[self::IMAGETYPE]) {
            case self::IMAGE_GIF:
                $result = @imagecreatefromgif($filename);
                break;

            case self::IMAGE_JPG:
                $result = @imagecreatefromjpeg($filename);
                break;

            case self::IMAGE_PNG:
                $result = @imagecreatefrompng($filename);
                break;
        }

        if (is_resource($result)) {
            return $result;
        }

        return null;
    }

    /**
     * Rotate the image by a given angle.
     * @param int $angle One of the ROTATION_* constants of this class.
     * @return $this
     * @throws \Exception
     */
    public function rotate($angle)
    {
        // Clockwise instead of counter clockwise
        $rotationAngle = 360 - $angle;

        // Get the handle and throw an exception when it couldn't be loaded
        $image = $this->getHandle();
        if (empty($image)) {
            throw new \Exception('Could not get image handle.');
        }

        // Rotate
        $rotated = imagerotate($image, $rotationAngle, 1);

        // Save the result over the current image
        switch ($this->imageInfo[self::IMAGETYPE]) {
            case self::IMAGE_GIF:
                imagegif($rotated, $this->getPath() . $this->getFilename());
                break;

            case self::IMAGE_JPG:
                imagejpeg($rotated, $this->getPath() . $this->getFilename(), 100);
                break;

            case self::IMAGE_PNG:
                imagepng($rotated, $this->getPath() . $this->getFilename(), 0);
                break;

            default:
                throw new \Exception('Unknown image format. Can not save.');
        }

        // Reset the imageInfo property as a new image has been created
        $this->imageInfo = null;

        // Chainable
        return $this;
    }

    /**
     * Convert to another image format.
     * @param type $newImageType
     * @throws \Exception
     * @return $this
     */
    public function convert($newImageType)
    {
        // Get the handle and throw an exception when it couldn't be loaded
        $handle = $this->getHandle();
        if (empty($handle)) {
            throw new \Exception('Could not get image handle.');
        }

        // Get filename without extension
        $fileWithoutExt = $this->getPath() . substr(
            $this->getFilename(),
            0,
            strrpos($this->getFilename(), '.')
        );

        // Convert
        switch ($newImageType) {
            case self::IMAGE_GIF:
                $file = $fileWithoutExt . '.gif';
                imagegif($handle, $file);
                break;

            case self::IMAGE_JPG:
                $file = $fileWithoutExt . '.jpg';
                imagejpeg($handle, $file, 100);
                break;

            case self::IMAGE_PNG:
                $file = $fileWithoutExt . '.png';
                imagepng($handle, $file, 0);
                break;

            default:
                throw new \Exception('Unknown image format. Can not save.');
        }

        // Delete the old image
        $this->delete();

        // Reload the new image
        $this->load($file);
        $this->imageInfo = null;

        // Chainable
        return $this;
    }
}
