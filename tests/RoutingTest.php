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

/**
 * Test the Framework\Application\Routing class.
 *
 * @author Jordi Jolink
 * @since 21-2-2015
 */
class RoutingTest extends FrameworkTest
{
    /**
     * Start the framework so we have auto loading.
     */
    public function setUp()
    {
        parent::setUp();
        $this->initFramework();
    }

    /**
     * Test the getRequestType function of the Routing class, which is an 
     * indirect input for the invocation of controller functions.
     */
    public function testGetRequestType()
    {
        // Test cases
        $requestTypes = [
            '' => 'Action',
            'GET' => 'Get',
            'get' => 'Get',
            'POST' => 'Post',
            'delete' => 'Delete'
        ];

        // Get the routing object
        $routing = new Framework\Application\Routing(
            $this->getRoutingConfiguration()
        );

        // Perform the tests on the cases
        foreach ($requestTypes as $header => $expected) {
            $_SERVER['REQUEST_METHOD'] = $header;
            $this->assertEquals($routing->getRequestType(), $expected);
        }

        // Perform with unset header
        if (isset($_SERVER['REQUEST_METHOD'])) {
            unset($_SERVER['REQUEST_METHOD']);
        }
        $this->assertEquals($routing->getRequestType(), 'Action');
    }

    /**
     * Test if routing is possible using the canRoute method.
     */
    public function testCanRoute()
    {
        // Create routers to test
        $goodRouting = new Framework\Application\Routing(
            $this->getRoutingConfiguration(),
            new Framework\Web\URI('/')
        );

        $badRouting = new Framework\Application\Routing(
            $this->getRoutingConfiguration(),
            new Framework\Web\URI('/this-route-does-not-exist')
        );

        // Perform the tests
        $this->assertTrue($goodRouting->canRoute());
        $this->assertFalse($badRouting->canRoute());
    }

    /**
     * Test the Routing object's getters:
     *  - getModule
     *  - getController
     *  - getFunction
     *  - getParams
     */
    public function testRoutingGetters()
    {
        // Define test data
        $testData = [
            '/' => [
                'Module' => 'Pub',
                'Controller' => 'Login',
                'Function' => 'index',
                'Params' => []
            ],
            '/system' => [
                'Module' => 'System',
                'Controller' => 'Dashboard',
                'Function' => 'index',
                'Params' => []
            ],
            '/system/' => [
                'Module' => 'System',
                'Controller' => 'Dashboard',
                'Function' => 'index',
                'Params' => []
            ],
            '/system/not-found' => [
                'Module' => null,
                'Controller' => null,
                'Function' => null,
                'Params' => null
            ],
            '/system/orders' => [
                'Module' => 'System',
                'Controller' => 'Orders',
                'Function' => 'index',
                'Params' => []
            ],
            '/system/orders/' => [
                'Module' => 'System',
                'Controller' => 'Orders',
                'Function' => 'index',
                'Params' => []
            ],
            '/system/orders/func' => [
                'Module' => 'System',
                'Controller' => 'Orders',
                'Function' => 'func',
                'Params' => []
            ],
            '/system/orders/func/' => [
                'Module' => 'System',
                'Controller' => 'Orders',
                'Function' => 'func',
                'Params' => []
            ],
            '/system/orders/func/param1/param2' => [
                'Module' => 'System',
                'Controller' => 'Orders',
                'Function' => 'func',
                'Params' => ['param1', 'param2']
            ],
            '/system/users/soneritics' => [
                'Module' => 'System',
                'Controller' => 'Users',
                'Function' => 'show',
                'Params' => ['soneritics']
            ],
            '/system/test/' => [
                'Module' => 'System',
                'Controller' => 'Test',
                'Function' => 'index',
                'Params' => []
            ]
        ];

        // Perform the tests with the defined test data
        foreach ($testData as $url => $expected) {
            $routing = new Framework\Application\Routing(
                $this->getRoutingConfiguration(),
                new Framework\Web\URI($url)
            );

            $this->assertEquals($routing->getModule(), $expected['Module']);
            $this->assertEquals($routing->getController(), $expected['Controller']);
            $this->assertEquals($routing->getFunction(), $expected['Function']);
            $this->assertEquals($routing->getParams(), $expected['Params']);
        }
    }

    /**
     * Defines the routing used for the test.
     * @return array
     */
    private function getRoutingConfiguration()
    {
        return [
            '/' => [
                'Module' => 'Pub',
                'Controller' => 'Login',
                'Function' => 'index'
            ],
            '/system' => [
                'Module' => 'System',
                'Controller' => 'Dashboard',
                'Function' => 'index'
            ],
            '/system/orders/*' => [
                'Module' => 'System',
                'Controller' => 'Orders'
            ],
            '/system/users/*' => [
                'Module' => 'System',
                'Controller' => 'Users',
                'Function' => 'show'
            ],
            '/system/test' => [
                'Module' => 'System',
                'Controller' => 'Test'
            ]
        ];
    }
}
