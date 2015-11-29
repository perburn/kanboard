<?php

namespace Kanboard\Auth;

use Kanboard\Core\Base;
use Kanboard\Core\Security\PreAuthenticationProviderInterface;
use Kanboard\User\ReverseProxyUserProvider;

/**
 * ReverseProxy Authentication Provider
 *
 * @package  auth
 * @author   Frederic Guillot
 */
class ReverseProxyAuth extends Base implements PreAuthenticationProviderInterface
{
    /**
     * User properties
     *
     * @access private
     * @var \Kanboard\User\ReverseProxyUserProvider
     */
    private $user = null;

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'ReverseProxy';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        $username = $this->request->getRemoteUser();

        if (! empty($username)) {
            $this->user = new ReverseProxyUserProvider($username);
            return true;
        }

        return false;
    }

    /**
     * Get user object
     *
     * @access public
     * @return null|\Kanboard\User\ReverseProxyUserProvider
     */
    public function getUser()
    {
        return $this->user;
    }
}
