<?php

namespace Kanboard\Api;

use JsonRPC\AuthenticationFailure;

/**
 * Base class
 *
 * @package  api
 * @author   Frederic Guillot
 */
class Auth extends Base
{
    /**
     * Check api credentials
     *
     * @access public
     * @param  string  $username
     * @param  string  $password
     * @param  string  $class
     * @param  string  $method
     */
    public function checkCredentials($username, $password, $class, $method)
    {
        $this->container['dispatcher']->dispatch('app.bootstrap');

        if ($username !== 'jsonrpc' && ! $this->authenticationManager->passwordAuthentication($username, $password)) {
            $this->checkProcedurePermission(true, $method);
            $this->userSession->initialize($this->user->getByUsername($username));
        } elseif ($username === 'jsonrpc' && $password === $this->config->get('api_token')) {
            $this->checkProcedurePermission(false, $method);
        } else {
            throw new AuthenticationFailure('Wrong credentials');
        }
    }
}
