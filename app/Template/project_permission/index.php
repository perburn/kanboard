<div class="page-header">
    <h2><?= t('Allowed Users') ?></h2>
</div>

<?php if ($project['is_everybody_allowed']): ?>
    <div class="alert"><?= t('Everybody have access to this project.') ?></div>
<?php else: ?>

    <?php if (empty($users)): ?>
        <div class="alert"><?= t('No user have been allowed specifically.') ?></div>
    <?php else: ?>
        <table>
            <tr>
                <th><?= t('User') ?></th>
                <th><?= t('Role') ?></th>
                <?php if ($project['is_private'] == 0): ?>
                    <th><?= t('Actions') ?></th>
                <?php endif ?>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $this->e($user['name'] ?: $user['username']) ?></td>
                <td><?= $this->user->getRoleName($user['role']) ?></td>
                <?php if ($project['is_private'] == 0): ?>
                <td>
                    <?= $this->url->link(t('Remove'), 'ProjectPermission', 'removeUser', array('project_id' => $project['id'], 'user_id' => $user['id']), true) ?>
                </td>
                <?php endif ?>
            </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>

    <?php if ($project['is_private'] == 0): ?>
    <div class="listing">
        <h3><?= t('Add new user') ?></h3>
        <form method="post" action="<?= $this->url->href('ProjectPermission', 'addUser', array('project_id' => $project['id'])) ?>" autocomplete="off" class="form-inline">
            <?= $this->form->csrf() ?>
            <?= $this->form->hidden('project_id', array('project_id' => $project['id'])) ?>

            <?= $this->form->label(t('Username'), 'user_id') ?>
            <?= $this->form->text('user_id', $values, $errors) ?>

            <?= $this->form->select('role', $roles, $values, $errors) ?>

            <input type="submit" value="<?= t('Add') ?>" class="btn btn-blue"/>
        </form>
    </div>
    <?php endif ?>

    <div class="page-header">
        <h2><?= t('Allowed Groups') ?></h2>
    </div>

    <?php if (empty($groups)): ?>
        <div class="alert"><?= t('No group have been allowed specifically.') ?></div>
    <?php else: ?>
        <table>
            <tr>
                <th><?= t('Group') ?></th>
                <th><?= t('Role') ?></th>
                <?php if ($project['is_private'] == 0): ?>
                    <th><?= t('Actions') ?></th>
                <?php endif ?>
            </tr>
            <?php foreach ($groups as $group): ?>
            <tr>
                <td><?= $this->e($group['name']) ?></td>
                <td><?= $this->user->getRoleName($group['role']) ?></td>
                <?php if ($project['is_private'] == 0): ?>
                <td>
                    <?= $this->url->link(t('Remove'), 'ProjectPermission', 'removeGroup', array('project_id' => $project['id'], 'group_id' => $group['id']), true) ?>
                </td>
                <?php endif ?>
            </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>

    <?php if ($project['is_private'] == 0): ?>
    <div class="listing">
        <h3><?= t('Add new group') ?></h3>
        <form method="post" action="<?= $this->url->href('ProjectPermission', 'addGroup', array('project_id' => $project['id'])) ?>" autocomplete="off" class="form-inline">
            <?= $this->form->csrf() ?>
            <?= $this->form->hidden('project_id', array('project_id' => $project['id'])) ?>

            <?= $this->form->label(t('Group Name'), 'group_id') ?>
            <?= $this->form->text('group_id', $values, $errors) ?>

            <?= $this->form->select('role', $roles, $values, $errors) ?>

            <input type="submit" value="<?= t('Add') ?>" class="btn btn-blue"/>
        </form>
    </div>
    <?php endif ?>

<?php endif ?>

<?php if ($project['is_private'] == 0): ?>
<hr/>
<form method="post" action="<?= $this->url->href('ProjectPermission', 'allowEverybody', array('project_id' => $project['id'])) ?>">
    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('id', array('id' => $project['id'])) ?>
    <?= $this->form->checkbox('is_everybody_allowed', t('Allow everybody to access to this project'), 1, $project['is_everybody_allowed']) ?>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</form>
<?php endif ?>
