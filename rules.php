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
		if ( file_exists('lib/extendedcpts/extended-cpts.php') ) {
			require_once 'lib/extendedcpts/extended-cpts.php'; # Extended CPTs
		} else {
			wp_die( __('Oops, a required library (Extended CPTs) appears to be missing!') );
		}
		
		# Set up Meta Box
		self::meta_box();		
		
		# Set up...
		self::actions(); # Actions
		self::filters(); # and Filters
		
	}
	
	/**
	 * WP plugin activation hook
	 */
	function activate() {
		# "Somewhere, something incredible is waiting to be known."
	}
	
	/**
	 * WP plugin deactivation hook
	 */
	function deactivate() {
		# "The universe is not required to be in perfect harmony with human ambition."
	}
	
	/**
	 * Register WP action hooks
	 */
	function actions() {
		
		add_action( 'init', array( $this, 'cpts' ) ); # Custom Post Types
		add_action( 'admin_menu', array( $this, 'remove_meta_boxes' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) ); # Guess...
		
	}
	
	function meta_box() {
		
		# Tell Meta Box where it is
		define( 'RWMB_URL', trailingslashit( dirname(__FILE__) . '/lib/meta-box' ) );
		define( 'RWMB_DIR', trailingslashit( dirname(__FILE__) . '/lib/meta-box' ) );
		
		# Require it, fail if it's disappeared
		if ( file_exists( RWMB_DIR . 'meta-box.php') ) {
			require_once RWMB_DIR . 'meta-box.php';
		} else {
			wp_die( __('Oops, a required library (Meta Box) appears to be missing!') );
		}
		
	}
	
	/**
	 * Register WP filter hooks
	 */
	function filters() {
		# “In all our searching, the only thing we've found that makes the emptiness bearable is each other.”
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
	
	/**
	 * Get rid of unnecessary meta boxes for the Rules post type
	 */
	function remove_meta_boxes() {
		if ( is_admin() ) {
			remove_meta_box( 'authordiv', 'rule', 'normal' ); # Author
			remove_meta_box( 'pageparentdiv', 'rule', 'side' ); # Page Attributes
		}
	}
	
	function add_meta_boxes() {
		add_meta_box( 'information', __('Information'), array( $this, 'meta_box_information' ), 'rule', 'normal', 'core' );
	}
	
	function meta_box_information( $post ) {
		echo 'Hello, world!';
	}
	
}

new Rules;