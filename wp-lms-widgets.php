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
	public function widget( $args, $instance ) {
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
		?>
<div class="mp-pusher" id="mp-pusher">
	
	<nav id="mp-menu" class="mp-menu">
		<div class="mp-level">
			<h2 class="icon icon-world">Active Classes</h2>
			<ul>
			<?
			foreach($all_pages as $k => $c){
				$id = $c->ID;
				$title = $c->post_title;
				$status = get_post_meta($id, '_status', true);
				if($status == "active"){
					$instructor = get_post_meta($id, '_instructor', true);
					$assignments = $page_query->query( 
						array( 
						'post_type' => 'assignment',
						'post_status' => 'publish',
						'_instructor' => $instructor,
						'posts_per_page' => -1 
						) 
					);
				?>
				<li class="icon icon-arrow-left">
					<a class="icon icon-display" href="#"><?= $title; ?></a>
					<div class="mp-level">
						<h2 class="icon icon-display"><?= $title; ?></h2>
						<a class="mp-back" href="#">back</a>
						
						<ul>
						<?
						$assign_title = "";
						$assign_id = "";
						foreach($assignments as $key => $assign) {
							$assign_title = $assign->post_title;
							$assign_id = $assign->ID;
							$course = get_post_meta($assign_id, '_course', true);
							$assign_instructor = get_post_meta($assign_id, '_instructor', true);

							if($course == $id && $instructor == $assign_instructor){
							?>
							<li>
								<a class="icon icon-diamond" href="#"><?= $assign_title; ?></a>
							</li>
						<?php 
							}
							//echo $course." ".$title;
						} 
						?>
						</ul>
					</div>
				</li>
						<?php
				}	
			}
			?>
			</ul>
		</div>
	</nav>
</div>
			<?php
	}

	public function update( $new_instance, $old_instance ) {
		// Save widget options
	}

	public function form( $instance ) {
		// Output admin widget options form
	}

}

$wp_lms_widgets = new wp_lms_widgets();

?>