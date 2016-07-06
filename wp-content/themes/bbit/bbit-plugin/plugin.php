<?php
/*
Plugin Name: 	Bbit Plugin
Plugin URI: 	http://bbit.vn
Description: 	Bbit Plugin is the newest and most complete SEO Wordpress Plugin on the market! Also it has the most unique feature, that cannot be found on any existing plugins on the market. It’s called SEO MASS OPTIMIZATION and it allows you to mass optimize all your post/pages/custom post types in just seconds!
Version: 		1.0
Author: 		Pham Quang Bao
Author URI: 	http://bbit.vn
*/
! defined( 'ABSPATH' ) and exit;

// Derive the current path and load up bbit
$plugin_path = dirname(__FILE__) . '/';
if(class_exists('bbit') != true) {
    require_once($plugin_path . 'bbit-framework/framework.class.php');

	// Initalize the your plugin
	$bbit = new bbit();

	// Add an activation hook
	register_activation_hook(__FILE__, array(&$bbit, 'activate'));
	add_action( 'after_setup_theme', array(&$bbit, 'lang_init') );
}