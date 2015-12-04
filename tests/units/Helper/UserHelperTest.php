<?php

require_once __DIR__.'/../Base.php';

use Kanboard\Helper\User;
use Kanboard\Model\Project;
use Kanboard\Model\ProjectUserRole;
use Kanboard\Model\User as UserModel;
use Kanboard\Core\Security\Role;

class UserHelperTest extends Base
{
    public function testInitials()
    {
        $h = new User($this->container);

        $this->assertEquals('CN', $h->getInitials('chuck norris'));
        $this->assertEquals('A', $h->getInitials('admin'));
    }

    public function testIsProjectAdministrationAllowedForApplicationManagerWhoAreProjectMember()
    {
        $h = new User($this->container);
        $p = new Project($this->container);
        $pp = new ProjectUserRole($this->container);
        $u = new UserModel($this->container);

        // We create our user
        $this->assertEquals(2, $u->create(array('username' => 'unittest', 'password' => 'unittest')));

        // We create a project and set our user as project manager
        $this->assertEquals(1, $p->create(array('name' => 'UnitTest')));
        $this->assertTrue($pp->addUser(1, 2, Role::PROJECT_MEMBER));

        // We fake a session for him
        $this->container['sessionStorage']->user = array(
            'id' => 2,
            'is_admin' => false,
            'is_project_admin' => true,
        );

        $this->assertTrue($h->hasProjectAccess('project', 'create', 1));
    }

    public function testIsProjectAdministrationAllowedForApplicationManagerWhoAreProjectManager()
    {
        $h = new User($this->container);
        $p = new Project($this->container);
        $pp = new ProjectUserRole($this->container);
        $u = new UserModel($this->container);

        // We create our user
        $this->assertEquals(2, $u->create(array('username' => 'unittest', 'password' => 'unittest')));

        // We create a project and set our user as project manager
        $this->assertEquals(1, $p->create(array('name' => 'UnitTest')));
        $this->assertTrue($pp->addUser(1, 2, Role::PROJECT_MANAGER));

        // We fake a session for him
        $this->container['sessionStorage']->user = array(
            'id' => 2,
            'is_admin' => false,
            'is_project_admin' => true,
        );

        $this->assertTrue($h->hasProjectAccess('project', 'create', 1));
        $this->assertTrue($h->hasProjectAccess('project', 'edit', 1));
    }

    public function testIsProjectAdministrationAllowedForProjectMember()
    {
        $h = new User($this->container);
        $p = new Project($this->container);
        $pp = new ProjectUserRole($this->container);
        $u = new UserModel($this->container);

        // We create our user
        $this->assertEquals(2, $u->create(array('username' => 'unittest', 'password' => 'unittest')));

        // We create a project and set our user as project member
        $this->assertEquals(1, $p->create(array('name' => 'UnitTest')));
        $this->assertTrue($pp->addUser(1, 2, Role::PROJECT_MEMBER));

        // We fake a session for him
        $this->container['sessionStorage']->user = array(
            'id' => 2,
            'is_admin' => false,
            'is_project_admin' => false,
        );

        $this->assertFalse($h->hasProjectAccess('project', 'create', 1));
        $this->assertFalse($h->hasProjectAccess('project', 'edit', 1));
    }

    public function testIsProjectAdministrationAllowedForProjectManager()
    {
        $h = new User($this->container);
        $p = new Project($this->container);
        $pp = new ProjectUserRole($this->container);
        $u = new UserModel($this->container);

        // We create our user
        $this->assertEquals(2, $u->create(array('username' => 'unittest', 'password' => 'unittest')));

        // We create a project and set our user as project member
        $this->assertEquals(1, $p->create(array('name' => 'UnitTest')));
        $this->assertTrue($pp->addUser(1, 2, Role::PROJECT_MANAGER));

        // We fake a session for him
        $this->container['sessionStorage']->user = array(
            'id' => 2,
            'is_admin' => false,
            'is_project_admin' => false,
        );

        $this->assertFalse($h->hasProjectAccess('project', 'create', 1));
        $this->assertTrue($h->hasProjectAccess('project', 'edit', 1));
    }

    public function testIsProjectManagementAllowedForProjectMember()
    {
        $h = new User($this->container);
        $p = new Project($this->container);
        $pp = new ProjectUserRole($this->container);
        $u = new UserModel($this->container);

        // We create our user
        $this->assertEquals(2, $u->create(array('username' => 'unittest', 'password' => 'unittest')));

        // We create a project and set our user as project member
        $this->assertEquals(1, $p->create(array('name' => 'UnitTest')));
        $this->assertTrue($pp->addUser(1, 2, Role::PROJECT_MEMBER));

        // We fake a session for him
        $this->container['sessionStorage']->user = array(
            'id' => 2,
            'is_admin' => false,
            'is_project_admin' => false,
        );

        $this->assertFalse($h->hasProjectAccess('project', 'create', 1));
        $this->assertFalse($h->hasProjectAccess('project', 'edit', 1));
    }

    public function testIsProjectManagementAllowedForProjectManager()
    {
        $h = new User($this->container);
        $p = new Project($this->container);
        $pp = new ProjectUserRole($this->container);
        $u = new UserModel($this->container);

        // We create our user
        $this->assertEquals(2, $u->create(array('username' => 'unittest', 'password' => 'unittest')));

        // We create a project and set our user as project member
        $this->assertEquals(1, $p->create(array('name' => 'UnitTest')));
        $this->assertTrue($pp->addUser(1, 2, Role::PROJECT_MANAGER));

        // We fake a session for him
        $this->container['sessionStorage']->user = array(
            'id' => 2,
            'is_admin' => false,
            'is_project_admin' => false,
        );

        $this->assertFalse($h->hasProjectAccess('project', 'create', 1));
        $this->assertTrue($h->hasProjectAccess('project', 'edit', 1));
    }
}
