<?php
/**
 * IssuePress
 *
 * @package   IssuePress_Admin
 * @author    Matthew Simo <matthew.simo@liftux.com>
 * @license   GPL-2.0+
 * @link      http://issuepress.co
 * @copyright 2014 Matthew Simo
 */

/**
 * IssuePress_Admin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package IssuePress_Admin
 * @author  Matthew Simo <matthew.simo@liftux.com>
 */
class IssuePress_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

    $this->admin_includes();

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = IssuePress::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

    // Run anything that the plugin might require in 'init' action
    add_action( 'init', array( $this, 'on_init' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( '@TODO', array( $this, 'action_method_name' ) );
		add_filter( '@TODO', array( $this, 'filter_method_name' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

  public static function admin_includes(){
    
  }

  /**
   * Called when wordpress action 'init' is fired.
   *
   * @since 1.0.0
   *
   * @return Void.
   */
  public static function on_init(){


    // Create Support Request Custom Post Type
    $support_request_labels = apply_filters( 'ip_support_request_labels', array(
      'name' => __( 'Support Requests' ),
      'singular_name' => __( 'Support Request' ),
    ));

    $support_request_args = array(
      'labels' => $support_request_labels,
      'public' => true,
      'has_archive' => false,
    );

    register_post_type( 'ip_support_request', apply_filters( 'ip_support_request_post_type_args', $support_request_args)); 


    // Create Support Request Sections Custom Taxonomy
    $support_section_labels = apply_filters( 'ip_support_section_labels', array(
        'name' => __( 'Support Sections' ),
        'singular_name' => __( 'Support Section' ),
        'add_new_item' => __( 'Add New Support Section' ),
        'new_item_name' => __( 'New Support Section' )
    ));

    $support_section_args = apply_filters( 'ip_support_section_args', array(
      'labels' => $support_section_labels 
    ));

    register_taxonomy( 'ip_support_section', 'ip_support_request', $support_section_args);


    // Create Support Request Labels Custom Taxonomy
    $support_label_labels = apply_filters( 'ip_support_label_labels', array(
      'name' => __( 'Support Labels' ),
      'singular_name' => __( 'Support Label' ),
      'add_new_item' => __( 'Add New Support Label' ),
      'new_item_name' => __( 'New Support Label' )
    ));

    $support_label_args = apply_filters( 'ip_support_label_args', array(
      'labels' => $support_label_labels 
    ));

    register_taxonomy( 'ip_support_label', 'ip_support_request', $support_label_args);

  }


	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), IssuePress::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), IssuePress::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'IssuePress Settings', $this->plugin_slug ),
			__( 'IssuePress', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

}
