<?php

if ( !class_exists( 'IP_New_Request_Form_Widget' ) ) {

	class IP_New_Request_Form_Widget extends WP_Widget {

		public function __construct() {
			global $IssuePress;

			$options = array(
				'description' => __( 'Display the IP Support Request Form', $IssuePress->get_plugin_name() )
			);

			parent::__construct(
				'ip_widget_new_request_form',
				__( 'IP Support Request Form Widget', $IssuePress->get_plugin_name() ),
				$options
			);

		}

		public function widget($args, $instance) {

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			echo do_shortcode('[ip_support_form]');

			echo $args['after_widget'];

		}

		public function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
			$title = $instance['title']; ?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

<?php

		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
			$instance['title'] = strip_tags($new_instance['title']);
			return $instance;
		}


		/**
		 * Register the widget
		 *
		 * @since			1.0.0 
		 * @uses register_widget()
		 */
		public static function register_widget() {
			register_widget( 'IP_New_Request_Form_Widget' );
		}


	}
}
