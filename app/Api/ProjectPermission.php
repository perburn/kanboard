<?php

namespace Kanboard\Api;

/**
 * ProjectPermission API controller
 *
 * @package  api
 * @author   Frederic Guillot
 */
class ProjectPermission extends \Kanboard\Core\Base
{
    public function getMembers($project_id)
    {
        return $this->projectUserRole->getAllUsers($project_id);
    }

    public function revokeUser($project_id, $user_id)
    {
        return $this->projectUserRole->removeUser($project_id, $user_id);
    }

    public function allowUser($project_id, $user_id)
    {
        return $this->projectUserRole->addUser($project_id, $user_id);
    }
}
