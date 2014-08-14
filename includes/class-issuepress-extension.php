<?php

/**
 * This is the recommended Parent Class for IP Extensions.
 * We expose the class if IssuePress is activated,
 * you can subclass your extension from this class.
 * There are some useful methods for interacting with IssuePress Core.
 *
 * @package IssuePress
 * @author  Matthew Simo <matthew.simo@liftux.com>
 */

// Check to see if the IP_Extension class already exists for namespace trampling.
if(!class_exists('IP_Extension')){

	class IP_Extension {

		/**
		 * Hold an instance of the IP Core
		 *
		 * @since 1.0.0
		 *
		 * @var object
		 */
		private $plugin;

		/**
		 * This Extension's ID
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $ext_id;

		/**
		 * This Extension's Name
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $ext_name;

		/**
		 * This Extension's Options
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		protected $ext_options;

		function __construct( $ext_id, $ext_name, $ext_options = array(), $ext_dependancies = array() ) {

			$this->plugin = IssuePress::get_instance();
			$this->ext_id = $ext_id;
			$this->ext_name = $ext_name;
			$this->ext_options = $ext_options;

			$this->register_extension($ext_id, $ext_name, $ext_options);
		}

		/**
		 * Register the extension with IssuePress Core
		 */
		public function register_extension($id, $name, $opts){

			array_push($this->plugin->extensions, array(
				'id' => $id,
				'name' => $name,
				'opts' => $opts
			));

		}


	}

}


