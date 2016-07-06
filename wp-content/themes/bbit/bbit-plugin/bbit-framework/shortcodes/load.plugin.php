<?php

// Derive the current path and load up bbit
$plugin_path = dirname(__FILE__) . '/';
if(class_exists('bbit') != true) {
    require_once('../framework.class.php');

	// Initalize the your plugin
	$bbit = new bbit();
}