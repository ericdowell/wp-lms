<?

class wp_lms_widgets extends WP_Widget {
	
	public function __construct () {
		parent::__construct( 
			'schedule', 
			'Schedule (WP LMS)',
            array( 'description' =>
            	'Displays list of active course.' 
            ) 
        );
	}
	public function widget( $args, $instance ) {
		//
		echo do_shortcode('[wp_lms_ins_schedule]'); 		
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