<?php

/**
 * Plugin Name: Premise WP Framework
 * Description: A Wordpress Framework for developers.
 * Plugin URI:	http://vallgoup.com/premise
 * Version:     1.0
 * Author:      Vallgroup LLC
 * Author URI:  http://vallgroup.com
 * License:     GPL
 * Text Domain: premise-text-domain
 * Domain Path: /languages
 */

/**
 * Premise WP Framework
 * @package Premise
 */

define( 'PREMISE_URL', plugins_url( '/', __FILE__ ) );
define( 'PREMISE_PATH', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', array( Premise_WP_FW_Class::get_instance(), 'premise_setup' ) );

class Premise_WP_FW_Class {
	/**
	 * Plugin instance.
	 *
	 * @see get_instance()
	 * @type object
	 */
	protected static $instance = NULL;

	public $plugin_url = PREMISE_URL;
	public $plugin_path = PREMISE_PATH;
	
	/**
	 * Constructor. Intentionally left empty and public.
	 *
	 * @see 	premise_setup()
	 * @since 	1.0
	 */
	public function __construct() {}

	/**
	 * Access this plugin’s working instance
	 *
	 * @since   1.0
	 * @return  object of this class
	 */
	public static function get_instance() {
		NULL === self::$instance and self::$instance = new self;
		
		return self::$instance;
	}

	/**
	 * Setup Premise
	 *
	 * @since   1.0
	 * @return  void
	 */
	public function premise_setup() {
		$this->do_includes();
		$this->premise_hooks();
	}

	/**
	 * Set Premise paths
	 *
	 * @since 1.0
	 * @return void
	 */
	protected function do_includes() {
		require( 'includes/includes.php' );
	}

	public function initiate_forms(){
		global $Premise_Form_Class;
		$Premise_Form_Class = new Premise_Form_Class;
	}

	/**
	 * Premise Hooks
	 */
	public function premise_hooks() {
		//forms
		add_action( 'wp_loaded', array( $this, 'initiate_forms' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'premise_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'premise_scripts' ) );
	}

	/**
	 * Premise CSS & JS
	 */
	public function premise_scripts() {
		//register styles
		wp_register_style( 'premise_font_awesome', PREMISE_URL . 'includes/font-awesome-4.2.0/css/font-awesome.min.css' );
		wp_register_style( 'premise_style_css'   , PREMISE_URL . 'css/premise.css', array( 'premise_font_awesome' ) );
		//register scripts
		wp_register_script( 'premise_script_js'  , PREMISE_URL . 'js/premise.js', array( 'jquery' ) );

		wp_enqueue_style( 'premise_style_css' );
		wp_enqueue_script( 'premise_script_js' );
	}

	/**
	 * Loads translation file.
	 *
	 * Accessible to other classes to load different language files (admin and
	 * front-end for example).
	 *
	 * @wp-hook init
	 * @param   string $domain
	 * @since   1.0
	 * @return  void
	 */
	public function load_language( $domain ) {
		load_plugin_textdomain(
			$domain,
			false,
			$this->plugin_path . 'languages'
		);
	}
}