<?php

if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

/*
// Register file to override the functions
// present in the file
*/
qa_register_plugin_overrides('qa-mailgun-integration.php');

qa_register_plugin_module('Module', 'qa-mailgun-admin_config.php', 'qa_mailgun_admin_config', 'Q2A MailGun Configuration');
