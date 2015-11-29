<?php

namespace Kanboard\Core\User;

/**
 * User Provider Interface
 *
 * @package  user
 * @author   Frederic Guillot
 */
interface UserProviderInterface
{
    /**
     * Return true to allow automatic user creation
     *
     * @access public
     * @return boolean
     */
    public function isUserCreationAllowed();

    /**
     * Get external id column name
     *
     * Example: google_id, github_id, gitlab_id...
     *
     * @access public
     * @return string
     */
    public function getExternalIdColumn();

    /**
     * Get internal id
     *
     * @access public
     * @return string
     */
    public function getInternalId();

    /**
     * Get external id
     *
     * @access public
     * @return string
     */
    public function getExternalId();

    /**
     * Get user role
     *
     * @access public
     * @return string
     */
    public function getRole();

    /**
     * Get username
     *
     * @access public
     * @return string
     */
    public function getUsername();

    /**
     * Get full name
     *
     * @access public
     * @return string
     */
    public function getName();

    /**
     * Get user email
     *
     * @access public
     * @return string
     */
    public function getEmail();

    /**
     * Get groups
     *
     * @access public
     * @return array
     */
    public function getGroups();

    /**
     * Get extra user attributes
     *
     * Example: is_ldap_user, disable_login_form, notifications_enabled...
     *
     * @access public
     * @return array
     */
    public function getExtraAttributes();
}
