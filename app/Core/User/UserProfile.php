<?php

namespace Kanboard\Core\User;

use Kanboard\Core\Base;
use Kanboard\Core\Security\Role;
use Kanboard\Model\User;

/**
 * User Profile
 *
 * @package  user
 * @author   Frederic Guillot
 */
class UserProfile extends Base
{
    /**
     * Assign provider data to the local user
     *
     * @access public
     * @param  integer                $userId
     * @param  UserProviderInterface  $user
     * @return boolean
     */
    public function assign($userId, UserProviderInterface $user)
    {
        $profile = $this->user->getById($userId);

        $values = $this->filterProperties($profile, $this->getProperties($user));
        $values['id'] = $userId;

        if ($this->user->update($values)) {
            $profile = array_merge($profile, $values);
            $this->userSession->initialize($profile);
            return true;
        }

        return false;
    }

    /**
     * Synchronize user properties with the local database and create the user session
     *
     * @access public
     * @param  UserProviderInterface $user
     * @return boolean
     */
    public function initialize(UserProviderInterface $user)
    {
        if ($user->getInternalId()) {
            $profile = $this->user->getById($user->getInternalId());
        } elseif ($user->getExternalIdColumn() && $user->getExternalId()) {
            $profile = $this->initializeExternalAccount($user);
        }

        if (! empty($profile)) {
            $this->userSession->initialize($profile);
            return true;
        }

        return false;
    }

    public function unlinkUser($userId, UserProviderInterface $user)
    {
        return $this->user->update(array(
            'id' => $userId,
            $user->getExternalIdColumn() => '',
        ));
    }

    private function initializeExternalAccount(UserProviderInterface $user)
    {
        $profile = $this->user->getByExternalId($user->getExternalIdColumn(), $user->getExternalId());
        $properties = $this->getProperties($user);

        if (! empty($profile)) {
            $profile = $this->updateUser($profile, $properties);
        } elseif ($user->isUserCreationAllowed()) {
            $profile = $this->createUser($user, $properties);
        }

        return $profile;
    }

    private function updateUser(array $profile, array $properties)
    {
        $values = $this->filterProperties($profile, $properties);
        $values['id'] = $profile['id'];

        if (! empty($values)) {
            $result = $this->user->update($values);
            return $result ? array_merge($profile, $properties) : $profile;
        }

        return $profile;
    }

    private function createUser(UserProviderInterface $user, array $properties)
    {
        $id = $this->user->create($properties);

        if ($id === false) {
            $this->logger->error('Unable to create user profile: '.$user->getExternalId());
            return array();
        }

        return $this->user->getById($id);
    }

    private function getProperties(UserProviderInterface $user)
    {
        $properties = array(
            'username' => $user->getUsername(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'is_admin' => (int) ($user->getRole() === Role::APP_ADMIN),
            'is_project_admin' => (int) ($user->getRole() === Role::APP_MANAGER),
            $user->getExternalIdColumn() => $user->getExternalId(),
        );

        return array_merge($properties, $user->getExtraAttributes());
    }

    private function filterProperties(array $profile, array $properties)
    {
        $values = array();

        foreach ($properties as $property => $value) {
            if ($profile[$property] === '' || is_null($profile[$property])) {
                $values[$property] = $value;
            }
        }

        return $values;
    }
}
