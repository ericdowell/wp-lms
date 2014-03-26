<?

class wp_lms_widgets extends WP_Widget {
	
	public function __construct () {
		parent::__construct( 
			'active_courses', 
			'Active Courses Button',
            array( 'description' =>
            	'Displays button for active courses navigation.' 
            ) 
        );
	}
	public function widget( $args, $instance ) {
		//
		echo do_shortcode('[wp_lms_active_menu_button class="decornone" text="Active Courses"]'); 		
	}

	public function update( $new_instance, $old_instance ) {
		// Save widget options
	}

	public function form( $instance ) {
		// Output admin widget options form
		?>
		<p></p>
		<?php
	}

}

$wp_lms_widgets = new wp_lms_widgets();

?>