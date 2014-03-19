<?

class wp_lms_widgets extends WP_Widget {
	
	public function __construct () {
		parent::__construct( 
			'active_courses', 
			'Active Courses',
            array( 'description' =>
            	'Displays list of active courses as navigation.' 
            ) 
        );
	}
	function widget( $args, $instance ) {
		// Widget output
		$page_query = new WP_Query();
		$all_pages = $page_query->query( 
			array( 
			'post_type' => 'course',
			'post_status' => 'publish', 
			'posts_per_page' => -1 
			) 
		);
		//echo "<pre>";print_r($all_pages);echo "</pre>";
		foreach($all_pages as $k => $c){
			$id = $c->ID;
			$title = $c->post_title;
			$status = get_post_meta($id, '_status', true);
			if($status == "active"){
			?>
			<h1><?= $title; ?></h1>
			<?php
			}
		}
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
	}

	function form( $instance ) {
		// Output admin widget options form
	}

}

$wp_lms_widgets = new wp_lms_widgets();

?>