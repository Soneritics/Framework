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
namespace Framework\Script;

use Framework\IO\File;

/**
 * Check whether a script is already running.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since 24-5-2015
 */
class SingleInstance
{
    /**
     * Instance name.
     * @var string
     */
    private $name;

    /**
     * File to write to.
     * @var File
     */
    private $file;

    /**
     * Temporary directory.
     * @var string
     */
    private $tempDir = '/tmp/single-instance/';

    /**
     * The number of minutes a script is allowed to run.
     * @var int
     */
    private $allowedRunMinutes = 15;

    private $endOnDestruct = false;

    /**
     * Construct the object with the instance name.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->updateTempDir();
    }

    /**
     * When the instance has been started by this object, it must also be ended
     * by this instance. Either by implicitly calling the stop() method or
     * destructing it.
     */
    public function __destruct()
    {
        if ($this->endOnDestruct) {
            $this->stop();
        }
    }

    /**
     * Set the temp directory.
     * @param string $tempDir
     * @return SingleInstance
     * @throws \Exception
     */
    public function setTempDir($tempDir)
    {
        if (!empty($this->file)) {
            throw new \Exception('Can not change the temp directory.');
        }

        $this->tempDir = $tempDir;
        $this->updateTempDir();
        return $this;
    }

    /**
     * Add a unique identifier to the temp dir.
     */
    protected function updateTempDir()
    {
        $this->tempDir .= md5(__DIR__) . '/';
    }

    /**
     * Set the allowed runtime in minutes for the instance.
     * @param int $minutes
     * @return \Framework\Script\SingleInstance
     */
    public function setAllowedRuntime($minutes)
    {
        $this->allowedRunMinutes = $minutes;
        return $this;
    }

    /**
     * Start the instance.
     * @return \Framework\Script\SingleInstance
     */
    public function start()
    {
        $this->getFile()->set($this->name);
        $this->getFile()->setChmod(0777);
        $this->endOnDestruct = true;
        return $this;
    }

    /**
     * Stop the instance.
     * @return \Framework\Script\SingleInstance
     */
    public function stop()
    {
        $this->getFile()->delete();
        return $this;
    }

    /**
     * Update the timestamp that the instance is running.
     * @return \Framework\Script\SingleInstance
     */
    public function update()
    {
        $this->getFile()->touch();
        return $this;
    }

    /**
     * Return whether an instance is already running.
     * @return boolean
     */
    public function isRunning()
    {
        if (!$this->getFile()->exists()) {
            return false;
        }

        $minutesRunning = floor(
            (time() - $this->getFile()->getModificationTime()) / 60
        );

        return $minutesRunning < $this->allowedRunMinutes;
    }

    /**
     * Get the file object for this instance.
     * @return File
     * @throws \Exception
     */
    private function getFile()
    {
        if (empty($this->file)) {
            $this->file = (new File)
                ->setFilename($this->name)
                ->setPath($this->tempDir);

            $path = $this->file->getPath();
            if (!file_exists($path) && !@mkdir($path, 0777, true)) {
                throw new \Exception("Directory {$path} could not be created.");
            }
        }

        return $this->file;
    }
}
