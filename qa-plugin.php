<?php
/**
 * Q2A Latest users - plugin to Question2Answer
 * @author Arkadiusz Waluk <arkadiusz@waluk.pl>
 */

/*
	Plugin Name: Latest users
	Plugin URI: https://github.com/awaluk/q2a-latest-users
	Plugin Description: Show latest registered and latest logged users
	Plugin Version: 1.0
	Plugin Date: 2017-09-23
	Plugin Author: Arkadiusz Waluk
	Plugin Author URI: http://waluk.pl
	Plugin License: MIT
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: https://raw.githubusercontent.com/awaluk/q2a-latest-users/master/metadata.json
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_module('page', 'latest-users-page.php', 'latest_users_page', 'Latest users module');

qa_register_plugin_layer('latest-users-layer.php', 'Latest users layer');

qa_register_plugin_phrases('latest-users-lang-*.php', 'latest_users');
