<?php

namespace Kanboard\Core\Ldap;

/**
 * LDAP Query
 *
 * @package ldap
 * @author  Frederic Guillot
 */
class Query
{
    /**
     * LDAP client
     *
     * @access private
     * @var Client
     */
    private $client = null;

    /**
     * Query result
     *
     * @access private
     * @var array
     */
    private $entries = array();

    /**
     * Constructor
     *
     * @access public
     * @param  Client $client
     * @param  array  $entries
     */
    public function __construct(Client $client, array $entries = array())
    {
        $this->client = $client;
        $this->entries = $entries;
    }

    /**
     * Execute query
     *
     * @access public
     * @param  string    $baseDn
     * @param  string    $filter
     * @param  array     $attributes
     * @return Query
     */
    public function execute($baseDn, $filter, array $attributes)
    {
        $sr = ldap_search($this->client->getConnection(), $baseDn, $filter, $attributes);
        if ($sr === false) {
            return $this;
        }

        $entries = ldap_get_entries($this->client->getConnection(), $sr);
        if ($entries === false || count($entries) === 0 || $entries['count'] == 0) {
            return $this;
        }

        $this->entries = $entries;

        return $this;
    }

    /**
     * Return true if the query returned a result
     *
     * @access public
     * @return boolean
     */
    public function hasResult()
    {
        return ! empty($this->entries);
    }

    /**
     * Return subset of entries
     *
     * @access public
     * @param  string   $key
     * @param  mixed    $default
     * @return array
     */
    public function getAttribute($key, $default = null)
    {
        return isset($this->entries[0][$key]) ? $this->entries[0][$key] : $default;
    }

    /**
     * Return one entry from a list of entries
     *
     * @access public
     * @param  string   $key         Key
     * @param  string   $default     Default value if key not set in entry
     * @return string
     */
    public function getAttributeValue($key, $default = '')
    {
        return isset($this->entries[0][$key][0]) ? $this->entries[0][$key][0] : $default;
    }
}
