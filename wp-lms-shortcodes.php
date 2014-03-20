<?

class wp_lms_shortcodes extends wp_lms {

	public function active_courses_menu($atts){
		//scripts and styles
    add_action( 'wp_footer', array($this, 'footer_scripts' ), 100 );
    //ob_start();
    //output
		$page_query = new WP_Query();
		$all_pages = $page_query->query( 
			array( 
			'post_type' => 'course',
			'post_status' => 'publish', 
			'posts_per_page' => -1 
			) 
		);
		usort( $all_pages, array($this, 'sort_menu_order') );
		//echo "<pre>";print_r($all_pages);echo "</pre>";
		?>
<div class="container">
	<div class="mp-pusher" id="mp-pusher">		
		<nav id="mp-menu" class="mp-menu">
			<div class="mp-level">
				<h2 class="icon icon-world">Active Classes</h2>
				<ul>
				<?
				foreach($all_pages as $k => $c){
					$id = $c->ID;
					$course_title = $c->post_title;
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
						<a class="icon icon-display" href="#"><?= $course_title; ?></a>
						<div class="mp-level">
							<h2 class="icon icon-display"><?= $course_title; ?></h2>
							<a class="mp-back" href="#">back</a>
							
							<ul>
							<?
							$assign_title = "";
							$assign_id = "";
							usort( $assignments, array($this, 'sort_menu_order') );
							foreach($assignments as $key => $assign) {
								$assign_title = $assign->post_title;
								$assign_id = $assign->ID;
								$course = get_post_meta($assign_id, '_course', true);
								$assign_instructor = get_post_meta($assign_id, '_instructor', true);
								$assign_parent = $assign->post_parent;
								//$next = $this->find_next_assignment($id, $instructor, $key, $assignments);
								
								//foreach( $pages as $k => $p ){ 
								if( $assign_parent == 0 && $course == $id && $instructor == $assign_instructor ) {
									$page_children = get_page_children( $assign_id, $assignments );
									$hasChildren = "";
									if( !empty( $page_children ) ) {
										$hasChildren = " children";
										//$parentName = $assign->post_name;
										usort( $assignments, array($this, 'sort_menu_order') );
										?>
							<li class="icon icon-arrow-left">
								<a class="icon icon-t-shirt" href="#"><?= $assign_title; ?></a>
								<div class="mp-level">
									<h2 class="icon icon-t-shirt"><?= $assign_title; ?></h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<?php
										foreach( $page_children as $w => $child ) {
											$c_title = $child->post_title;
											?>
										<li><a href="#"><?= $c_title; ?></a></li>
											<?php
										}
										?>
									</ul>
								</div>
							</li>
										<?php
									}
									else if( empty($page_children ) ) {
										?>
							<li class="icon icon-arrow-left">
								<a class="icon icon-t-shirt" href="#"><?= $assign_title; ?></a>
							</li>
										<?php
									}
								}
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
		<div class="scroller">
			<div class="scoller-inner">
		<?php
		//ob_end_flush();
	}

	public function active_courses_menu_button($atts){
		?>
		<p><a href="#" id="trigger" class="menu-trigger">Open/Close Menu</a></p>
		<?php
	}

	public function active_courses_list($atts) {
		?>

		<?php
	}

	public function footer_scripts() {
      ?>
      <script src="<?= $this->plugin_base_url.'inc/ml-push-menu/js/classie.js'; ?>"></script>
      <script src="<?= $this->plugin_base_url.'inc/ml-push-menu/js/mlpushmenu.js'; ?>"></script>
      <script>
        new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ), {
            type : 'cover'
        } );
      </script>
		</div><!-- .scroller-inner -->
		</div><!-- .scroller -->
	</div><!-- .pusher -->
</div><!-- .container -->
      <?
    }
}
$wp_lms_shortcodes = new wp_lms_shortcodes();
?>