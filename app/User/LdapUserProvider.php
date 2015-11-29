<?php

namespace Kanboard\User;

use Kanboard\Core\User\UserProviderInterface;
use Kanboard\Core\Security\Role;

/**
 * LDAP User Provider
 *
 * @package  user
 * @author   Frederic Guillot
 */
class LdapUserProvider implements UserProviderInterface
{
    /**
     * User properties
     *
     * @access private
     * @var array
     */
    private $user = array();

    /**
     * Constructor
     *
     * @access public
     * @param  array $user
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    /**
     * Return true to allow automatic user creation
     *
     * @access public
     * @return boolean
     */
    public function isUserCreationAllowed()
    {
        return LDAP_ACCOUNT_CREATION;
    }

    /**
     * Get internal id
     *
     * @access public
     * @return string
     */
    public function getInternalId()
    {
        return '';
    }

    /**
     * Get external id column name
     *
     * @access public
     * @return string
     */
    public function getExternalIdColumn()
    {
        return 'username';
    }

    /**
     * Get external id
     *
     * @access public
     * @return string
     */
    public function getExternalId()
    {
        return $this->user['username'];
    }

    /**
     * Get user role
     *
     * @access public
     * @return string
     */
    public function getRole()
    {
        if ($this->user['is_admin'] == 1) {
            return Role::APP_ADMIN;
        } elseif ($this->user['is_project_admin'] == 1) {
            return Role::APP_MANAGER;
        }

        return Role::APP_USER;
    }

    /**
     * Get username
     *
     * @access public
     * @return string
     */
    public function getUsername()
    {
        return $this->user['username'];
    }

    /**
     * Get full name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->user['name'];
    }

    /**
     * Get user email
     *
     * @access public
     * @return string
     */
    public function getEmail()
    {
        return $this->user['email'];
    }

    /**
     * Get groups
     *
     * @access public
     * @return array
     */
    public function getGroups()
    {
        return array();
    }

    /**
     * Get extra user attributes
     *
     * @access public
     * @return array
     */
    public function getExtraAttributes()
    {
        return array(
            'is_ldap_user' => 1,
        );
    }
}
