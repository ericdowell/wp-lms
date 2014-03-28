<?php 

class wp_lms_shortcodes extends wp_lms {

	public function active_courses_menu($atts){
		switch($this->menu) {
			case 'jquery':
			$this->active_courses_menu_jquery($atts);
				break;
			case 'css3d':
				$this->active_courses_menu_css_3dtransforms($atts);
				break;
			default:

				break;
		}
	}

	public function active_courses_menu_css_3dtransforms($atts){
		//scripts and styles
	    add_action( 'wp_footer', array($this, 'footer_scripts_css_3dtransforms' ), 100 );
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
			<div class="mp-level">
				<h2 class="icon icon-world">Active Classes</h2>
				<a class="mp-close" href="#">Close</a>
				<div class="mp-scroller">
				<ul class="mp-list">
				<?php
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
						<a class="icon  icon-study" href="#"><?= $course_title; ?></a>
						<div class="mp-level">
							<h2 class="icon icon-study"><?= $course_title; ?></h2>
							<a class="mp-back" href="#">back</a>
							<div class="mp-scroller">
							<ul>
								<li><a href="<?php echo get_permalink($id) ;?>timeline">Timeline</a></li>
								<li><a href="<?php echo get_permalink($id) ;?>">Syllabus</a></li>
							<?
							$assign_title = "";
							$assign_id = "";
							usort( $assignments, array($this, 'sort_menu_order') );
							foreach($assignments as $key => $assign) {
								$assign_title = $assign->post_title;
								$assign_name = $assign->post_name;
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
								<a class="icon" href="#"><?= $assign_title; ?></a>
								<div class="mp-level">
									<h2 class="icon"><?= $assign_title; ?></h2>
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
							<li>
								<a href="<?php echo get_permalink($assign_id) ;?>"><?= $assign_title; ?></a>
							</li>
										<?php
									}
								}
							} 
							?>
							</ul>
							</div>
						</div>
					</li>
							<?php
					}
				}
				?>
				</ul>
				</div>
			</div>
		</nav>
		<div class="add-scroll">
			<div class="inner-add-scroll">
			 <? echo do_shortcode('[wp_lms_active_menu_button class="decornone" text="Active Courses"]'); ?>
			</div>
		</div>
		<div class="scroller">
			<div class="scoller-inner">
		<?php
		//ob_end_flush();
	}

	public function active_courses_menu_jquery($atts){
		//scripts and styles
	    add_action( 'wp_footer', array($this, 'footer_scripts_jquery' ), 100 );
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
		<div id="menu-jquery">
			<nav class='jquery-nav'>
				<h2><i class="fa fa-reorder"></i>Active Classes</h2>
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
							'_instructor_nu_assignment' => $instructor,
							'posts_per_page' => -1 
							) 
						);
					?>
					<li>
						<a href="#"><i class="fa fa-laptop"></i><?= $course_title; ?></a>
						<h2><i class="fa fa-laptop"></i><?= $course_title; ?></h2>
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
							<li>
								<a href="#"><i class="fa fa-phone"></i><?= $assign_title; ?></a>
		                        <h2><i class="fa fa-phone"></i><?= $assign_title; ?></h2>
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
							</li>
									<?php
								}
								else if( empty($page_children ) ) {
									?>
							<li>
								<a href="<?php echo get_permalink($assign_id) ;?>"><?= $assign_title; ?></a>
							</li>
									<?php
								}
							}
						} 
						?>
						</ul>
					</li>
							<?php
					}
				}
				?>
				</ul>
			</nav>
		</div>
		<div class="container">
		<?php
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
		<div class="wp-lms wp-lms-list">
		<ul class="mast-ul">
			<li class="assign top"><a href="<?php echo get_permalink($id) ;?>timeline"  class="assign-title">Timeline</a></li>
			<li class="assign top"><a href="<?php echo get_permalink($id) ;?>" class="assign-title">Syllabus</a></li>
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
				$assignment_type = get_post_meta($assign_id, "_assign_type", true);
				$info = "";
				if($assignment_type == 'assignment') {
					$points = get_post_meta($assign_id, "_points", true);
					$_class_start = get_post_meta($assign_id, "_class_start", true);
					if( !empty($_class_start) ) {
						$_class_start = explode(".", $_class_start);
						$start_week = $_class_start[0];
						$start_class = $_class_start[1];
					}
					$_class_due = get_post_meta($assign_id, "_class_due", true);
					if( !empty($_class_due) ) {
						$_class_end = explode(".", $_class_due);
						$end_week = $_class_end[0];
						$end_class = $_class_end[1];
					}
					$est_time = get_post_meta($assign_id, "_est_time", true);
					if( !empty($est_time) ) {
						$est_time = explode(",", $est_time);
						$time = $est_time[0];
						$measure = $est_time[1];
					}
					$turn_type = get_post_meta($assign_id, "_turn_type", true);
					if(strstr($turn_type, "_")) {
						$turn_type = str_replace("_", " ", $turn_type);
					}
					$info = " - Due: W".$end_week." C".$end_class." - P".$points;
				}
				$page_children = get_page_children( $assign_id, $assignments );
				$hasChildren = "";
				if( !empty( $page_children ) ) {
				?>
				<li class="parent-li top"><h3 class="hgrp hgrp-3 parent-title"><?= $assign_title; ?></h3>
					<ul class="parent-ul">

					<?
					$hasChildren = " children";
					//$parentName = $assign->post_name;
					usort( $page_children, array($this, 'sort_menu_order') );
					?>
					<?php
					foreach( $page_children as $w => $child ) {
						$cid = $child->ID;
						$c_title = $child->post_title;
						$assignment_type = get_post_meta($cid, "_assign_type", true);
						$infochild = "";
						if($assignment_type == 'assignment') {
							$points = get_post_meta($cid, "_points", true);
							$_class_start = get_post_meta($cid, "_class_start", true);
							if( !empty($_class_start) ) {
								$_class_start = explode(".", $_class_start);
								$start_week = $_class_start[0];
								$start_class = $_class_start[1];
							}
							$_class_due = get_post_meta($cid, "_class_due", true);
							if( !empty($_class_due) ) {
								$_class_end = explode(".", $_class_due);
								$end_week = $_class_end[0];
								$end_class = $_class_end[1];
							}
							$est_time = get_post_meta($cid, "_est_time", true);
							if( !empty($est_time) ) {
								$est_time = explode(",", $est_time);
								$time = $est_time[0];
								$measure = $est_time[1];
							}
							$turn_type = get_post_meta($cid, "_turn_type", true);
							if(strstr($turn_type, "_")) {
								$turn_type = str_replace("_", " ", $turn_type);
							}
							$infochild = " - Due: W".$end_week." C".$end_class." - P".$points;
						}
						?>
					<li class="assign child"><a href="<?php echo get_permalink($cid) ;?>"  class="assign-title"><?= $c_title; ?></a><?= $infochild; ?></li>
						<?php
					}
					?>
					</ul>
				</li>
				<?php
				}
				else if( empty($page_children ) ) {
					?>
		<li class="assign top">
			<a href="<?php echo get_permalink($assign_id) ;?>" class="assign-title"><?= $assign_title; ?></a><?= $info; ?>
		</li>
					<?php
				}
			}
		} 
		?>
		</ul>
		</div>
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
		<div class="wp-lms-list">
		<ul>
		<?php
		foreach($all_pages as $k => $course) {
			$cid = $course->ID;
			$ctitle = $course->post_title;
			$status = get_post_meta($cid, "_status", true);
			$ins = get_post(get_post_meta($cid, "_instructor", true));
			if($status == 'inactive') {
		?>
			<li><a href="<?php echo get_permalink($cid) ;?>" title="<?= $ctitle; ?> - <?= $ins->post_title; ?>"><?= $ctitle; ?> - <?= $ins->post_title; ?></a></li>
		<?php
			}
		}
		?>
		</ul>
		</div>
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
		<div class="wp_lms wp-lms-list ins_schedule<?= $class; ?>">
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
				<li><? //echo $this->get_day_sname(0); ?> <a href="<?= get_permalink($cid); ?>assignments/" title="<?= $ctitle; ?>"><?= $ctitle; ?></a></li>
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

	public function footer_scripts_css_3dtransforms() {
		//wp_enqueue_script('jquery', array('jquery'));
      ?>
      <script src="<?= $this->plugin_base_url.'inc/ml-push-menu/js/classie.js'; ?>"></script>
      <script src="<?= $this->plugin_base_url.'inc/ml-push-menu/js/mlpushmenu.js'; ?>"></script>
      <script>
 		new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ), {
    		type : 'cover'
		} );
		//jQuery('.site a').unbind('click');
      </script>
			</div><!-- .scroller-inner -->
		</div><!-- .scroller -->
	</div><!-- .pusher -->
</div><!-- .container -->
      <?
    }
    	public function footer_scripts_jquery() {
      ?>
	<script src="<?= $this->plugin_base_url.'inc/MultiLevelPushMenu_v2.1.4/jquery.multilevelpushmenu.min.js'; ?>"></script>
     <script type="text/javascript" src="<?= $this->plugin_base_url.'inc/MultiLevelPushMenu_v2.1.4/theme_integration.js'; ?>"></script>
  	</div>
      <?
    }

}
$wp_lms_shortcodes = new wp_lms_shortcodes();
?>