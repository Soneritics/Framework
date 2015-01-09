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
 * Class that handles file folders.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  14-9-2014
 */
class Folders
{
    private static $paths = [];

    /**
     * Make sure an ending slash is removed from a path.
     *
     * @param  string $path
     * @return string
     */
    private function removeTrailingSlash($path)
    {
        if (strlen($path) > 1
            && in_array(substr($path, -1), ['\\', '/'])
        ) {
            $path = substr($path, 0, -1);
        }

        return $path;
    }

    /**
     * Set the root path of the framework, on which other paths are based on.
     *
     * @param  string $rootPath The path of the framework's root.
     * @return \Framework\IO\Folders
     */
    public function setFrameworkRootPath($rootPath)
    {
        $root = $this->removeTrailingSlash($rootPath);

        static::$paths = array_merge(
            static::$paths,
            [
                'root' => $root,
                'package' => "{$root}/Soneritics",
                'framework' => "{$root}/Soneritics/Framework",
                'framework-vendor' => "{$root}/vendor",
            ]
        );

        return $this;
    }

    /**
     * Set the application root directory.
     *
     * @param string $rootPath Path of the application's root.
     */
    public function setAppRootPath($rootPath = null)
    {
        $root = $this->removeTrailingSlash($rootPath === null ? getcwd() : $rootPath);

        static::$paths = array_merge(
            static::$paths,
            [
                'app' => $root,
                'web' => "{$root}/Web",
                'vendor' => "{$root}/vendor",
                'config' => "{$root}/Config",
                'layouts' => "{$root}/Layouts",
                'modules' => "{$root}/Modules",
            ]
        );

        return $this;
    }

    /**
     * Get the path identified by $name.
     *
     * @param  type $name
     * @return type
     * @throws FatalException
     */
    public function get($name)
    {
        if (isset(static::$paths[$name])) {
            return static::$paths[$name];
        }

        throw new Framework\Exceptions\FatalException('Path not set: ' . $name);
    }
}
