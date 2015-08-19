<h1>Merchant Index Page</h1>

<p><?php echo lang('index_subheading'); ?></p>

<div id="infoMessage"><?php echo $message; ?></div>

<table cellpadding=0 cellspacing=10>
    <tr>
        <th><?php echo lang('index_username_th'); ?></th>
        <th><?php echo lang('index_password_th'); ?></th>
        <th><?php echo lang('index_fname_th'); ?></th>
        <th><?php echo lang('index_lname_th'); ?></th>
        <th><?php echo lang('index_email_th'); ?></th>
        <th><?php echo lang('index_status_th'); ?></th>
        <th><?php echo lang('index_action_th'); ?></th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user->password_visible, ENT_QUOTES, 'UTF-8'); ?></td>     
            <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, lang('index_active_link')) : anchor("auth/activate/" . $user->id, lang('index_inactive_link')); ?></td>
            <td><?php echo anchor("auth/edit_user/" . $user->id, 'Edit'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p><?php echo anchor('auth/create_user', lang('index_create_user_link')) ?></p>