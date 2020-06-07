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

/*
    DEFAULT THEME - DSJAS
    =====================

    This is the themeing files included in the default installation of DSJAS.
    It contains HTML and PHP files required to load and display the default theme.

    This file should never be accessed directly, and instead should only be
    required by a file which has already bootstrapped the site.
    This means that your script must have defined the ABSPATH constants
    and preformed other required bootstrapping tasks before the page
    can be displayed.


    For more information of theming and creating your own themes, please refer to the
    API documentation for themes and plugins.
*/

require_once(ABSPATH . INC . "api/theme/General.php");

// Theme entry point
function getTheme()
{ ?>

    <body class="container-fluid" style="text-align: center">

        <?php
        // If we want the success page, display that now
        if (isset($_GET["success"]) && $_GET["success"] == true) { ?>
            <div class="jumbotron">
                <h1>You have been logged out</h1>
                <p>You have been successfully logged out of your account, you can now return home or sign in again.</p>

                <?php addModuleDescriptor("logged_out_header");  ?>
            </div>

            <span>
                <a class="btn btn-primary" href="/">Go to the Homepage</a>
                <a class="btn btn-secondary" href="/user/Login">Sign in again</a>
                <?php addModuleDescriptor("logged_out_actions");  ?>
            </span>

            <p>
                <p class="text-sm text-secondary">To make absolutely sure you are signed out, you may wish to close all browser windows</p>

                <?php addModuleDescriptor("logged_out_footer");  ?>

            <?php
            die();
        } ?>

            <p style="text-align: left">One moment, you're being signed out...</p>

            <script>
                console.log("You will be signed out in around 5 seconds, please wait...");

                setTimeout(function() {
                    document.clear();
                    document.writeln("Signing out now...");
                    document.location = "/user/Logout.php?logout=true"
                }, 750)
            </script>

    </body>
<?php }