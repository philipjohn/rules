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
	
	public $meta_prefix = 'rules_';
	
	/**
	 * In the beginning, Phil created a Class...
	 */
	function __construct() {
		
		# (De-)activation hooks
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		
		# Grab the required libraries
		if ( file_exists( dirname(__FILE__) . '/lib/extendedcpts/extended-cpts.php' ) ) {
			require_once 'lib/extendedcpts/extended-cpts.php'; # Extended CPTs
		} else {
			wp_die( __('Oops, a required library (Extended CPTs) appears to be missing!') );
		}
		
		# Set up Meta Box
		self::meta_box();

		# Allow for the post type to be changed
		if ( !defined('RULES_POST_TYPE') ) {
			define( 'RULES_POST_TYPE', 'rule' );
		}
		
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
	 * Loads the meta box plugin
	 */
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
	 * Register WP action hooks
	 */
	function actions() {
		
		add_action( 'init', array( $this, 'cpts' ) ); # Custom Post Types
		add_action( 'admin_menu', array( $this, 'remove_meta_boxes' ) );
		add_action( 'rwmb_after_information', array( $this, 'display_source' ) );
		
	}
	
	/**
	 * Register WP filter hooks
	 */
	function filters() {
		
		add_filter ( 'rwmb_meta_boxes', array( $this, 'add_meta_boxes' ) );
		
	}
	
	/**
	 * Register our Custom Post Types
	 */
	function cpts() {
		
		# Rule type
		register_extended_post_type( RULES_POST_TYPE, array(
			
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
			remove_meta_box( 'authordiv', RULES_POST_TYPE, 'normal' ); # Author
			remove_meta_box( 'pageparentdiv', RULES_POST_TYPE, 'side' ); # Page Attributes
		}
	}
	
	/**
	 * Adds all of our meta boxes to our Rules post type
	 * @param array $meta_boxes An array of existing meta boxes
	 * @return array
	 */
	function add_meta_boxes( $meta_boxes ) {
		
		$prefix = $this->meta_prefix;
		
		$meta_boxes[] = array(
			'id'       => 'information',
			'title'    => __('Information'),
			'pages'    => array( RULES_POST_TYPE ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __('Description'),
					'desc'  => __('Provide a friendly description of this rule to explain it\'s purpose'),
					'id'    => $prefix . 'description',
					'type'  => 'textarea',
				),
			)
		);
		
		return $meta_boxes;
		
	}
	
	/**
	 * Shows who or what is the source of a rule
	 */
	function display_source() {
		global $post;
		
		# Where has this rule originated from?
		$source = get_post_meta( $post->ID, 'rules_source', true );
		if ( !empty($source) ) {
			echo '<p><strong>' . __('Generated by:') . '</strong> ' . $source . '</p>';
		}
		
		# What is the status of this rule?
		$status = get_post_meta( $post->ID, 'rules_status', true );
		if ( !empty($status) ) {
			echo '<p><strong>' . __('Status:') . '</strong> ' . $status . '</p>';
		}
		
	}
	
}

new Rules;