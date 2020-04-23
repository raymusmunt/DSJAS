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

require("../include/Bootstrap.php");

require(ABSPATH . INC . "Customization.php");
require(ABSPATH . INC . "Theme.php");


$config = new Configuration(false, true, false, false);
if ($config->getKey(ID_THEME_CONFIG, "config", "use_default")) {
    $useTheme = DEFAULT_THEME;
} else {
    $useTheme = $config->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
}


$theme = new Theme("Error.php", "/", $useTheme);
$theme->loadTheme();
$theme->displayTheme();