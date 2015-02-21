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

require_once __DIR__ . '/../Soneritics/Framework/AutoLoader.php';

/**
 * Test the AutoLoader class.
 *
 * @author Jordi Jolink
 * @since 21-2-2015
 */
class AutoLoaderTest extends FrameworkTest
{
    /**
     * Create a new Autoloader object.
     * @return Framework\Autoloader
     */
    private function getAutoLoader()
    {
        return (new Framework\AutoLoader)
            ->addRootPath(__DIR__ . '/../Soneritics');
    }

    /**
     * Test for registering and unregistering the auto loader.
     */
    public function testRegisterUnregisterAutoload()
    {
        // Current autoloaders
        $autoloaders = count(spl_autoload_functions());

        // Create autoloader object
        $autoloader = $this->getAutoLoader();

        // Register the autoloader
        $autoloader->register();

        // Assert that there are new registered autoloaders
        $addedAutoloader = count(spl_autoload_functions());
        $this->assertGreaterThan($autoloaders, $addedAutoloader);

        // Unregister auto loader, so we should be back to the old situation
        $autoloader->unregister();
        $this->assertEquals($autoloaders, count(spl_autoload_functions()));
    }

    /**
     * Test if an autoloader is unregistered upon destruction.
     */
    /* @FIXME: Destructor calls unregister function, but doesn't actually unregister
    public function testDestructAutoloaderObject()
    {
        // Current autoloaders
        $autoloaders = count(spl_autoload_functions());

        // Create autoloader object
        $autoloader = $this->getAutoLoader();

        // Register the autoloader
        $autoloader->register();

        // Assert that there are new registered autoloaders
        $addedAutoloader = count(spl_autoload_functions());
        $this->assertGreaterThan($autoloaders, $addedAutoloader);

        // Destroy auto loader, so we should be back to the old situation
        unset($autoloader);
        $this->assertEquals($autoloaders, count(spl_autoload_functions()));
    }
    */

    /**
     * Test the function that handles auto loading, without the
     * registering took place.
     */
    public function testAutoloaderFunction()
    {
        // Get the initialized autoloader object
        $autoloader = $this->getAutoLoader();

        // Test with framework Redirect class
        $existingClass = 'Framework\Headers\Redirect';
        $notExistingClass = 'Framework\Headers\IDontExist';

        // Test with existing class
        $this->assertFalse(class_exists($existingClass));
        $this->assertTrue($autoloader->loadClass($existingClass));
        $this->assertTrue(class_exists($existingClass));

        // Test with non existing class
        $this->assertFalse($autoloader->loadClass($notExistingClass));
    }
}
