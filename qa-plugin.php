<?php

/*
    Plugin Name: Latest users
    Plugin URI: https://github.com/awaluk/q2a-latest-users
    Plugin Description: Show latest registered and latest logged users
    Plugin Version: 1.1.0
    Plugin Date: 2020-08-29
    Plugin Author: Arkadiusz Waluk
    Plugin Author URI: https://waluk.pl
    Plugin License: MIT
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Minimum PHP Version: 7.0
    Plugin Update Check URI: https://raw.githubusercontent.com/awaluk/q2a-latest-users/master/metadata.json
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit();
}

qa_register_plugin_module('page', 'src/latest-users-page.php', 'latest_users_page', 'Latest users module');

qa_register_plugin_layer('src/latest-users-layer.php', 'Latest users layer');

qa_register_plugin_phrases('lang/*.php', 'latest_users');
