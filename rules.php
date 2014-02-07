<?php
/*
	Plugin Name: Rules
	Plugin URI: http:#github.com/philipjohn/rules
	Description: Rules for WordPress allows you to set activity triggers and specify actions for your site to perform, all through the dashboard.
	Author: Philip John
	Version: 0.1-alpha
	Author URI: http:#philipjohn.me.uk
	Text Domain: rules
	Domain Path: /languages
 */

Class Rules {
	
	/**
	 * In the beginning, Phil created a Class...
	 */
	function __construct() {
		
		# (De-)activation hooks
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		
		# Grab the required libraries
		require_once 'lib/extendedcpts/extended-cpts.php'; # Extended CPTs
		
		# Set up ACF, hide from normal users
		if ( !defined('WP_LOCAL_DEV') ) {
			define( 'ACF_LITE' , true );
		}
		require_once 'lib/advanced-custom-fields/acf.php';
		
		
		# Set up...
		self::actions(); # Actions
		self::filters(); # and Filters
		
	}
	
	/**
	 * WP plugin activation hook
	 */
	function activate() {
		# Do nothing, for now
	}
	
	/**
	 * WP plugin deactivation hook
	 */
	function deactivate() {
		# Do nothing, for now
	}
	
	/**
	 * Register WP action hooks
	 */
	function actions() {
		
		add_action( 'init', array( $this, 'cpts' ) ); # Custom Post Types
		
	}
	
	/**
	 * Register WP filter hooks
	 */
	function filters() {
		
		
		
	}
	
	/**
	 * Register our Custom Post Types
	 */
	function cpts() {
		
		# Rule type
		register_extended_post_type( 'rule', array(
			
			'menu_icon' => 'dashicons-book',
			
			'menu_position' => 65,
			
			'supports' => array(
				'title',
				'author',
				'page-attributes',
			),
		
		) );
		
	}
	
}

new Rules;