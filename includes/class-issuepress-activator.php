<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    IssuePress
 * @subpackage IssuePress/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    IssuePress
 * @subpackage IssuePress/includes
 * @author     Your Name <email@example.com>
 */
class IssuePress_Activator {

	/**
	 * Fired During Activation 
	 *
	 * @since    	1.0.0
	 */
	public static function activate() {

		// Flush Rewrites
		flush_rewrite_rules();

		add_action( 'init', array( 'IssuePress_Activator', 'init_taxonomies'), 12 );


		// Add Activation Notice
		$notices = get_option('ip_deferred_admin_notices', array());
		$notices[] = array(
			'message' => __( 'Welcome to IssuePress - To get started', 'issuepress' ) . ' <a href="' . admin_url('edit-tags.php?taxonomy=ip_support_section&post_type=ip_support_request') . '" target="_blank" title="Add Support Sections">' . __( "add your support sections", 'issuepress' ) . '</a> &amp; <a href="' . admin_url('admin.php?page=issuepress_options') . '" target="_blank" title="IP Settings Page">' . __( "configure your settings", 'issuepress' ) . '</a>.',
			'class'		=> "updated"
		);

		update_option( 'ip_deferred_admin_notices', $notices );

	}

	/**
	 * Init Support Section Taxonomy
	 *
	 * Adds a general term to the support sections taxonomy if no terms exist.
	 */
	public static function init_taxonomies() {

		// Check if there are terms in support sections taxonomy, if not - create "General" term.
		$sections_terms = get_terms( get_ip_support_section_taxonomy(), array( 'hide_empty' => false ) );

		if( empty( $sections_terms ) ) {

			wp_insert_term( "General", get_ip_support_section_taxonomy() );

		}

	}


}
