<?php

/*
Welcome to Dave-Smith Johnson & Son family bank!

This is a tool to assist with scam baiting, especially with scammers attempting to
obtain bank information or to attempt to scam you into giving money.

This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
is free to use and change for all users. Scam bait as much as you want!

This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!

Please, waste these people's time as much as possible. It's fun and it does good for everyone.

*/

require("AdminBootstrap.php");

require(ABSPATH . INC . "Users.php");
require(ABSPATH . INC . "Update.php");
require(ABSPATH . INC . "Administration.php");

require(ABSPATH . INC . "api/theme/General.php");


if (isset($_GET["purgeNotifications"])) {
    purgeAdministrationNotices();
}


$majorVersion = getMajorVersion();
$minorVersion = getMinorVersion();
$patch = getPatchVersion();
$semantic = getSemanticVersion();

$adminNotices = getAdministrationNotices();
$areAdminNotices = count($adminNotices) >= 1;

$newAccount = currentUserIsNew(true);


if ($newAccount) {
    makeCurrentUserUsed(true);
}

?>

<html>
<?php require(ABSPATH . INC . "components/AdminSidebar.php"); ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Welcome back, <?php echo (getCurrentUsername(true)); ?></h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Statistics</button>
                <button class="btn btn-sm btn-outline-secondary">Settings</button>
            </div>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Notifications</h3>
            <a class="btn btn-danger" href="/admin/dashboard.php?purgeNotifications">Clear</a>
        </div>

        <div class="card-body">
            <?php
            if ($areAdminNotices || isset($_GET["purgeNotifications"]) || $newAccount) {
                handleAdminNotices($adminNotices);

                if (isset($_GET["purgeNotifications"])) { ?>
                    <div class="alert alert-success">
                        <p><strong>Notifications cleared:</strong> Notifications were cleared. This message will be removed automatically.</p>
                    </div>
                <?php }

                if ($newAccount) { ?>
                    <div class="alert alert-info">
                        <p><strong>Welcome to DSJAS!</strong> DSJAS has detected that your account is new. Welcome! You have reached the admin dashboard,
                            where you can perform the majority of the admin operations of the site. More information on what to do next
                            and on what actions you may wish to perform is available on the wiki, accessible through the help link in the
                            sidebar. Have fun and happy scambaiting! <i>(This message will only appear once and will be cleared automatically)</i></p>
                    </div>
                <?php }
            } else { ?>
                <p class="text-small text-muted">No notifications are available</p>
            <?php } ?>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <h3 class="card-header">Quick information</h3>

        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>General overview</th>
                        <th></th>
                    </tr>
                </thead>

                <tr>
                    <td class="admin-info-key">Site name</td>
                    <td class="admin-info-value"><?php echo (getBankName()); ?></td>
                </tr>

                <tr>
                    <td class="admin-info-key">Site URL</td>
                    <td class="admin-info-value"><?php echo (getBankURL()); ?></td>
                </tr>

                <tr>
                    <td class="admin-info-key">Site version</td>
                    <td class="admin-info-value"><?php echo ($semantic); ?></td>
                </tr>

                <tr>
                    <td class="admin-info-key">Current theme</td>
                    <td class="admin-info-value"><?php echo (getCurrentThemeName()); ?></td>
                </tr>

                <thead class="thead-dark">
                    <tr>
                        <th>Accounts</th>
                        <th></th>
                    </tr>
                </thead>

                <tr>
                    <td class="admin-info-key">Amount of users</td>
                    <td class="admin-info-value"><?php echo (getNumberOfUsers(false)); ?></td>
                </tr>

                <tr>
                    <td class="admin-info-key">Amount of site users</td>
                    <td class="admin-info-value"><?php echo (getNumberOfUsers(true)); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <h3 class="card-header">Quick actions</h3>

        <div class="card-body">
            <div class="btn-group" role="group">
                <a class="btn btn-primary admin-quick-action-btn" href="/admin/settings/accounts.php?new_account">New site user</a>
                <a class="btn btn-secondary admin-quick-action-btn" href="/user/Apply.php">New user</a>
                <a class="btn btn-secondary admin-quick-action-btn" href="/admin/settings/accounts.php?new_bank_account">New bank account</a>
            </div>

            <div class="btn-group" role="group">
                <a class="btn btn-primary admin-quick-action-btn" href="/admin/settings/ext.php">Install a theme</a>
                <a class="btn btn-secondary admin-quick-action-btn" href="/admin/settings/ext.php">Install a plugin</a>
                <a class="btn btn-secondary admin-quick-action-btn" href="">Configure modules</a>
            </div>

            <a href="/admin/settings/ext.php?validate_theme" class="btn btn-success admin-quick-action-btn">Validate theme</a>

            <div class="btn-group" role="group">
                <a href="/admin/user/Logout" class="btn btn-danger admin-quick-action-btn">Logout</a>
                <a href="/admin/user/Logout" class="btn btn-secondary admin-quick-action-btn">Logout from bank</a>
            </div>

        </div>
    </div>
</div>

</html>