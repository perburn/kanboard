<?php

namespace Kanboard\Auth;

use Kanboard\Core\Base;
use Kanboard\Core\Ldap\Client as LdapClient;
use Kanboard\Core\Ldap\ClientException as LdapException;
use Kanboard\Core\Ldap\User as LdapUser;
use Kanboard\Core\Security\PasswordAuthenticationProviderInterface;
use Kanboard\User\LdapUserProvider;

/**
 * LDAP Authentication Provider
 *
 * @package  auth
 * @author   Frederic Guillot
 */
class LdapAuth extends Base implements PasswordAuthenticationProviderInterface
{
    /**
     * User properties
     *
     * @access private
     * @var \Kanboard\User\LdapUserProvider
     */
    private $user = null;

    /**
     * Username
     *
     * @access private
     * @var string
     */
    private $username = '';

    /**
     * Password
     *
     * @access private
     * @var string
     */
    private $password = '';

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'LDAP';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        try {

            $ldap = LdapClient::bind($this->getLdapServer(), $this->getLdapUsername(), $this->getLdapPassword());
            $profile = LdapUser::getProfile($ldap, $this->getLdapBaseDn(), $this->getLdapUserPattern());

            if (! empty($profile)) {
                $this->logger->debug('Found LDAP user profile, checking user credentials now');

                if ($ldap->authenticate($profile['ldap_id'], $this->password)) {
                    $this->user = new LdapUserProvider($profile);
                    return true;
                }
            }

            $this->logger->info('LDAP user profile not found');

        } catch (LdapException $e) {
            $this->logger->error($e->getMessage());
        }

        return false;
    }

    /**
     * Get user object
     *
     * @access public
     * @return null|LdapUserProvider
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set username
     *
     * @access public
     * @param  string $username
     */
    public function setUsername($username)
    {
        $this->username = $this->isLdapAccountCaseSensitive() ? $username : strtolower($username);
    }

    /**
     * Set password
     *
     * @access public
     * @param  string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get LDAP server name
     *
     * @access public
     * @return string
     */
    public function getLdapServer()
    {
        return LDAP_SERVER;
    }

    /**
     * Get LDAP bind type
     *
     * @access public
     * @return integer
     */
    public function getLdapBindType()
    {
        return LDAP_BIND_TYPE;
    }

    /**
     * Get LDAP Base DN
     *
     * @access public
     * @return string
     */
    public function getLdapBaseDn()
    {
        return LDAP_ACCOUNT_BASE;
    }

    /**
     * Return true if the LDAP username is case sensitive
     *
     * @access public
     * @return boolean
     */
    public function isLdapAccountCaseSensitive()
    {
        return LDAP_USERNAME_CASE_SENSITIVE;
    }

    /**
     * Get LDAP username (proxy auth)
     *
     * @access public
     * @return string
     */
    public function getLdapUsername()
    {
        switch ($this->getLdapBindType()) {
            case 'proxy':
                return LDAP_USERNAME;
            case 'user':
                return sprintf(LDAP_USERNAME, $this->username);
            default:
                return null;
        }
    }

    /**
     * Get LDAP password (proxy auth)
     *
     * @access public
     * @return string
     */
    public function getLdapPassword()
    {
        switch ($this->getLdapBindType()) {
            case 'proxy':
            case 'user':
                return LDAP_PASSWORD;
            default:
                return null;
        }
    }

    /**
     * Get LDAP username pattern
     *
     * @access public
     * @return string
     */
    public function getLdapUserPattern()
    {
        return sprintf(LDAP_USER_PATTERN, $this->username);
    }
}
