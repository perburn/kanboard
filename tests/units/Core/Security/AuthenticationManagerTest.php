<?php

require_once __DIR__.'/../../Base.php';

use Kanboard\Core\Security\AuthenticationManager;
use Kanboard\Auth\DatabaseAuth;

class AuthenticationManagerTest extends Base
{
    public function testRegister()
    {
        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));
        $provider = $authManager->getProvider('Database');

        $this->assertInstanceOf('Kanboard\Core\Security\AuthenticationProviderInterface', $provider);
    }

    public function testGetProviderNotFound()
    {
        $authManager = new AuthenticationManager($this->container);
        $this->setExpectedException('LogicException');
        $authManager->getProvider('Dababase');
    }

    public function testPasswordAuthenticationSuccessful()
    {
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_SUCCESS, array($this, 'onSuccess'));
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_FAILURE, array($this, 'onFailure'));

        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));

        $this->assertTrue($authManager->passwordAuthentication('admin', 'admin'));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(AuthenticationManager::EVENT_SUCCESS.'.AuthenticationManagerTest::onSuccess', $called);
        $this->assertArrayNotHasKey(AuthenticationManager::EVENT_FAILURE.'.AuthenticationManagerTest::onFailure', $called);
    }

    public function testPasswordAuthenticationFailed()
    {
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_SUCCESS, array($this, 'onSuccess'));
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_FAILURE, array($this, 'onFailure'));

        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));

        $this->assertFalse($authManager->passwordAuthentication('admin', 'wrong password'));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayNotHasKey(AuthenticationManager::EVENT_SUCCESS.'.AuthenticationManagerTest::onSuccess', $called);
        $this->assertArrayHasKey(AuthenticationManager::EVENT_FAILURE.'.AuthenticationManagerTest::onFailure', $called);
    }

    public function onSuccess($event)
    {
        $this->assertInstanceOf('Kanboard\Event\AuthSuccessEvent', $event);
        $this->assertEquals('Database', $event->getAuthType());
    }

    public function onFailure($event)
    {
        $this->assertInstanceOf('Kanboard\Event\AuthFailureEvent', $event);
    }
}
