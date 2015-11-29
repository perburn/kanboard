<?php

require_once __DIR__.'/../../Base.php';

use Kanboard\Core\Http\Request;

class RequestTest extends Base
{
    public function testGetRemoteUser()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getRemoteUser());

        $request = new Request($this->container, array(REVERSE_PROXY_USER_HEADER => 'test'), array(), array(), array(), array());
        $this->assertEquals('test', $request->getRemoteUser());
    }

    public function testIsHTTPS()
    {
        $request = new Request($this->container, array(), array(), array(), array());
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, array('HTTPS' => ''), array(), array(), array(), array());
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, array('HTTPS' => 'off'), array(), array(), array(), array());
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, array('HTTPS' => 'on'), array(), array(), array(), array());
        $this->assertTrue($request->isHTTPS());

        $request = new Request($this->container, array('HTTPS' => '1'), array(), array(), array(), array());
        $this->assertTrue($request->isHTTPS());
    }

    public function testGetCookie()
    {
        $request = new Request($this->container, array(), array(), array(), array(), array());
        $this->assertEmpty($request->getCookie('mycookie'));

        $request = new Request($this->container, array(), array(), array(), array(), array('mycookie' => 'miam'));
        $this->assertEquals('miam', $request->getCookie('mycookie'));
    }
}
