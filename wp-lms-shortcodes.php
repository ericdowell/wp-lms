<?

class wp_lms_shortcodes extends wp_lms {

	public function active_courses_menu($atts){
		//scripts and styles
	    add_action( 'wp_footer', array($this, 'footer_scripts' ), 5 );
	    //output
		$page_query = new WP_Query();
		$all_pages = $page_query->query( 
			array( 
			'post_type' => 'course',
			'post_status' => 'publish', 
			'posts_per_page' => -1, 
			'orderby' => 'title',
			'order' => 'ASC'
			) 
		);
		?>
<div class="container">
	<!-- push-wrapper -->
	<div class="mp-pusher" id="mp-pusher">		
		<!-- menu -->
		<nav id="mp-menu" class="mp-menu">
			<div class="mp-level" data-level="1">
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
					<li class="icon icon-arrow-left mp-level-container">
						<a class="icon icon-display" href="#"><?= $course_title; ?></a>
						<div class="mp-level" data-level="2">
							<h2 class="icon icon-display"><?= $course_title; ?></h2>
							<a class="mp-back" href="#">back</a>
							<ul>
								<li><a href="<?php echo get_permalink($id) ;?>timeline">Timeline</a></li>
								<li><a href="<?php echo get_permalink($id) ;?>">Syllabus</a></li>
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
											$cid = $child->ID;
											$c_title = $child->post_title;
											?>
										<li><a href="<?php echo get_permalink($cid) ;?>"><?= $c_title; ?></a></li>
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
							<li class="icon">
								<a class="icon icon-t-shirt" href="<?php echo get_permalink($assign_id) ;?>"><?= $assign_title; ?></a>
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
		extract( shortcode_atts( array(
      'text' => 'Menu',
      'icon' => 'svg',
      'class' => ''
		), $atts ) );
		if( !isset($atts['text']) ) $text = "Menu";
		else $text = $atts['text'];
		//Open/Close Menu
		?>
		<nav class="menu-button"><a href="#" id="trigger" class="menu-trigger<?= ' '.$atts['class']; ?>"><?= $text; ?></a></nav>
		<?php
	}

	public function active_courses_list($atts) {
		?>

		<?php
	}

	public function assignment_list($atts){
		global $post;
		$page_query = new WP_Query();
		$id = $post->ID;
		$pid = $post->post_parent;
		$course_title = $post->post_title;
		$status = get_post_meta($pid, '_status', true);
		$instructor = get_post_meta($pid, '_instructor', true);
		$assignments = $page_query->query( 
			array( 
			'post_type' => 'assignment',
			'post_status' => 'publish',
			'posts_per_page' => -1 
			) 
		);
		?>
		<ul>
			<li><a href="<?php echo get_permalink($id) ;?>timeline">Timeline</a></li>
			<li><a href="<?php echo get_permalink($id) ;?>">Syllabus</a></li>
		<?
		//print_r($assignments);
		usort( $assignments, array($this, 'sort_menu_order') );
		foreach($assignments as $key => $assign) {
			$assign_title = $assign->post_title;
			$assign_id = $assign->ID;
			$course = get_post_meta($assign_id, '_course', true);
			$assign_instructor = get_post_meta($assign_id, '_instructor', true);
			$assign_parent = $assign->post_parent;
			
			if( $assign_parent == 0 && $course == $pid && $instructor == $assign_instructor ) {
				$page_children = get_page_children( $assign_id, $assignments );
				$hasChildren = "";
				if( !empty( $page_children ) ) {
				?>
				<li><?= $assign_title; ?>
					<ul>

					<?
					$hasChildren = " children";
					//$parentName = $assign->post_name;
					usort( $assignments, array($this, 'sort_menu_order') );
					?>
					<?php
					foreach( $page_children as $w => $child ) {
						$cid = $child->ID;
						$c_title = $child->post_title;
						?>
					<li><a href="<?php echo get_permalink($cid) ;?>"><?= $c_title; ?></a></li>
						<?php
					}
					?>
					</ul>
				</li>
				<?php
				}
				else if( empty($page_children ) ) {
					?>
		<li>
			<a class="icon icon-t-shirt" href="#"><?= $assign_title; ?></a>
		</li>
					<?php
				}
			}
		} 
		?>
		<?php
	}

	public function inactive_courses_list($atts) {
		$page_query = new WP_Query();
		$all_pages = $page_query->query( 
			array( 
			'post_type' => 'course',
			'post_status' => 'publish', 
			'posts_per_page' => -1, 
			'orderby' => 'title',
			'order' => 'ASC'
			) 
		);
		?>
		<ul>
		<?php
		foreach($all_pages as $k => $course) {
			$cid = $course->ID;
			$ctitle = $course->post_title;
			$status = get_post_meta($cid, "_status", true);
			$ins = get_post(get_post_meta($cid, "_instructor", true));
			if($status == 'inactive') {
		?>
			<li><?= $ctitle; ?> - <?= $ins->post_title; ?></li>
		<?php
			}
		}
		?>
		</ul>
		<?php
	}

	public function portfolio_countdown($atts) {
		?>
		<span class="portfolio-countdown">Porfolio show is in v Weeks x Days y Hours z Minutes w Seconds</span>
		<?php
	}

	public function instructor_schedule($atts) {
		extract( shortcode_atts( array(
	      'class' => '',
	      'id' => ''
     	), $atts ) );
     	$class = ' '.$atts['class'].'';
		$page_query = new WP_Query();
		$args = array(
			'sort_column' => 'menu_order',
			'post_type' => 'instructor',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'
		);
		$page_query = new WP_Query();
		$all_pages = $page_query->query( $args );
		?>
		<div class="wp_lms ins_schedule<?= $class; ?>">
		<?
		foreach( $all_pages as $k => $in ){ 
			$id = $in->ID;
			$ins_name = explode(" ", $in->post_title);
			$ins_name_head = "Mr. ".$ins_name[1]."s";
			$ins_name = "Mr. ".$ins_name[1];
			$args = array(
				'sort_column' => 'menu_order',
				'post_type' => 'course',
				'post_status' => 'publish',
				'_instructor' => $id,
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC'
			);
			$courses = $page_query->query( $args );
			?>
			<h2><?= $ins_name_head; ?> Schedule</h2>
			<ul>
			<?
			$one = false;
			foreach($courses as $k => $c){
				$cid = $c->ID;
				$ctitle = $c->post_title;
				$ins = get_post_meta($cid, "_instructor", true);
				$cstatus = get_post_meta($cid, "_status", true);
				if( $ins == $id && $cstatus == "active") {
				?>
				<li><a href="<?= get_permalink($cid); ?>assignments/" title="<?= $ctitle; ?>"><?= $ctitle; ?></a></li>
				<?php	
				$one = true;
				}
			}
			if(!$one){
				echo "<li>".$ins_name." isn't teaching this term. :(</li>";
			}
			?>
			</ul>
			<?php
		}
		?>
		</div>
		<?php
	}

	public function example_url($atts) {
		extract( shortcode_atts( array(
	      'sub' => 'wi14wdim',
	      'domain' => 'example.com'
     ), $atts ) );
			$url = $atts['sub'].".".$atts['domain'];
		return $url;
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