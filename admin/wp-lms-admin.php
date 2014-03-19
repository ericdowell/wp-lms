<?php
/**
 *  Admin Page Object, Child of Plugin Object
 *  @since 1.0.0
 *  @updated 1.0.0
 **/
class wp_lms_admin extends wp_lms {
	/**
	 *  If no construct is defined in child, the Parent construct is used and hence all variables defined in it are inherited.
	**/

    /**
     *  Adds Menus and submenus to wp admin dashboard
     *  @since 1.0.0
     *  @updated 1.0.0
     **/
	public function admin_settings(){
		$this->menu_pages['main'] = add_menu_page( $this::$plugin_name, 'WP LMS Settings', 'manage_options', $this::$plugin_name, array( $this, 'main_options_page' ) );
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		
		//$this->menu_pages['schedule'] = add_submenu_page( "edit.php?post_type=session", 'WP LMS Schedule', 'Schedule Courses', 'manage_options', $this::$plugin_name.'_schedule', array( $this,"schedule_page" ), $this->plugin_img_url.'png/24/schedule.png' );
		//$this->menu_pages['assignments'] = add_submenu_page( "edit.php?post_type=course", 'WP LMS Assignments', 'Assignments', 'manage_options', $this::$plugin_name.'_assignment', array( $this,"assignment_page" ) );
		//$this->menu_pages['lectures'] = add_submenu_page( "edit.php?post_type=course", 'WP LMS Lectures', 'Lectures', 'manage_options', $this::$plugin_name.'_lecture', array( $this,"lecture_page" ) );
		//$this->menu_pages['settings'] = add_submenu_page( "edit.php?post_type=course", 'WP LMS Settings', 'Settings', 'manage_options', $this::$plugin_name.'_settings', array( $this,"settings_page" ) );
		//edit.php?post_type=student_directory
		$this->menu_pages['view-student'] = add_submenu_page( "edit.php?post_type=student_directory", 'WP LMS View Students', 'View Students', 'manage_options', $this::$plugin_name.'_view_student', array( $this,"view_student" ) );
		//$this->menu_pages['settings-student'] = add_submenu_page( "edit.php?post_type=student_directory", 'WP LMS Student Settings', 'Settings', 'manage_options', $this::$plugin_name.'_student_settings', array( $this,"student_settings" ) );
		$this->menu_pages['view-instructors'] = add_submenu_page( "edit.php?post_type=instructor", 'WP LMS View Instrustors', 'View Instrustors', 'manage_options', $this::$plugin_name.'_view_instructor', array( $this,"view_instructor" ) );
		//$this->menu_pages['view-course-students'] = add_submenu_page( "edit.php?post_type=course", 'WP LMS View Course Students', 'View Enrollment', 'manage_options', $this::$plugin_name.'_view_enrollment', array( $this,"view_enrollment" ) );
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	}//end admin_settings
	static public function page_url(){
		return admin_url( "edit.php?post_type=".$_GET['post_type']."&amp;page=".$_GET["page"] );
	}

    /**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function main_options_page(){
		$page_base = $this->page_url();
		ob_start();
		?>
		<div class="wp_lms settings">
			<h1>GrandPubbah</h1>

		</div>
		<?
		ob_end_flush();
	}//end main_options_page

	/**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function schedule_page(){
		$page_base = $this->page_url();
		$page_query = new WP_Query();
		ob_start();
		?>
		<style type="text/css">
			.wp_lms.wrap h1 {
				display: inline-block;
				width:80%;
			}
		</style>
		<div id="poststuff" class="wp_lms wrap schedule">
			<h1>Schedule</h1>
		<div id="post-body" class="columns-2">
			
			<form id="postbox-container-1" action="<?= $page_base; ?>" method="post">
			<?php
			wp_lms_html_gen::list_select( 'instructor', 'Instructors', "POST", $_POST );
			wp_lms_html_gen::list_select( 'course', 'Courses', "POST", $_POST );
			wp_lms_html_gen::list_select( 'session', 'Sessions', 'POST', $_POST);
			wp_lms_html_gen::date_set( 'Day/Time Active', 'no-dates', $_POST );
        	?>
			
			</form>
			<div id="post-body-content">
				<? wp_lms_html_gen::form_open(array("post_type" => "schedule", "showposts" => "10"), $page_base ); ?>
			</div>
		</div>
		</div>
		<?
		ob_end_flush();
	}//end main_options_page

	/**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function assignment_page(){
		$page_base = $this->page_url();
		//switch($tab)
		ob_start();
		?>
		<div class="wp_lms settings">
			<h1>Assignments</h1>
			<h2 class="nav-tab-wrapper">
				<a href="<?= $page_base;?>&amp;status=active" class="nav-tab" title="Active Courses">Active Courses</a>
				<a href="<?= $page_base;?>&amp;status=inactive" class="nav-tab" title="Inactive Courses">Inactive Courses</a>
			</h2>
			<h3>Course List</h3>
			<?php
			// 'post_type' => 'course',
	  //       'showposts' => 10,
	  //       'orderby' => $orderby,
	  //       'order' => strtoupper($order) 
			wp_lms_html_gen::form_open(array("post_type" => "course", "showposts" => "10", "orderby" => "title", "order" => "ASC"), $page_base ) ?>
		</div>
		<?
		ob_end_flush();
	}//end main_options_page

	/**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function lecture_page(){
		global $wpdb;
		$page_base = $this->page_url();
		// query_posts(array( 
		//         'post_type' => 'assignment',
		//         'course_assigned' => 562,
		//         'showposts' => 10,
		//         'orderby' => 'title',
		//         'order' => 'ASC'
		//     ) );
		ob_start();
		?>
		<div class="wp_lms wrap settings">
			<h1>Lectures</h1>
			<h2>
			<a href="http://grand/wp-admin/post-new.php?post_type=assignment&amp;course=568" class="add-new-h2">Add Assignment</a>
			</h2>
  			<? wp_lms_html_gen::form_open(array("post_type" => "assignment", "_course" => "566", "showposts" => "10"), $page_base ) ?>
  			<h2>Pages</h2>
			<?
			foreach( $this->menu_pages as $k => $v ) {
			?>
				<p><?= $k; ?> <?= $v; ?></p>
			<?
			}
			$class_num = "568";
			$instructor_num = "Douglas Brull";
			$the_post_type = 'lecture';
			$the_course_term = "course_num";
			$the_instructor_term = "instructor_name";
			$args = array(
				'sort_column' => 'menu_order',
				'post_type' => $the_post_type,
				"course_num" => $class_num,
				'instructor_name' => $instructor_num,
				'post_status' => 'publish'
			);
			$pages = get_pages( $args );
			$page_query = new WP_Query();
			$all_pages = $page_query->query( array( 'post_type' => $the_post_type, 'post_status' => 'publish', 'posts_per_page' => -1 ) );
			$parent_count = 0;
			foreach( $pages as $k => $p ){ 
				if( $p->post_parent == 0 && has_term( $class_num, $the_course_term, $p->ID ) && has_term( $instructor_num, $the_instructor_term, $p->ID ) ) {
					$parent_count++;
					$page_children = get_page_children( $p->ID, $all_pages );
					$hasChildren = "";
					if( !empty( $page_children ) ) {
						$hasChildren = " children";
						$parentName = $p->post_name;
						usort( $page_children, array($this, 'sort_menu_order') );
						?>
						<h1><?= $parentName; ?></h1>
						<?php
						foreach( $page_children as $key => $val ) {
							?>
							<h2><?= $val->post_title; ?></h2>
							<?php
						}

					}
					else if( empty($page_children ) ) {
						$parentName = $p->post_name;
						?>
						<h1><?= $parentName; ?></h1>
						<?php
					}
				}
				else {
					
				}
			}
			?>
		</div>
		<?
		ob_end_flush();
	}//end main_options_page

	 /**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function settings_page(){
		$page_base = $this->page_url();
		ob_start();
		?>
		<div class="wp_lms settings">
			<h1><?= $this::$name; ?> Settings Page</h1>
			
		</div>
		<?
		ob_end_flush();
	}//end settings_page

	/**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function view_student(){
		$page_base = $this->page_url();
		ob_start();
		?>
		<div class="wp_lms settings">
			<h1>View Student Information</h1>
			<?
			$args = array(
				'sort_column' => 'menu_order',
				'post_type' => 'student_directory',
				'post_status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC'
			);
			$pages = get_pages( $args );
			?>
			<section class="students">
			<?
			foreach( $pages as $k => $s ){ 
				?>
				<h1><?= $s->post_title; ?></h1>
				<h3>Enrollment: </h3>
				<ul>
				<? 
				$course_count = get_post_meta($s->ID, "_enroll_count", true); 
				//echo $course_count;
				if($course_count > 1) {
					//echo "true";
					for ($i=0; $i < $course_count; $i++) { 
						echo '<li>'.get_post(get_post_meta($s->ID, '_course'.$i, true))->post_title.'</li>';
					}
				}
				else if ($course_count == 1) {
					echo '<li>'.get_post(get_post_meta($s->ID, '_course1', true))->post_title.'</li>';
				}
				else if( empty($course_count)) {
					echo '<li>Enrollment not set yet.</li>';
				}
				?>
				</ul>
				<?
				$custom_field_keys = get_post_custom_keys($s->ID);
				foreach ( $custom_field_keys as $key => $value ) {
				    $valuet = trim($value);
				    if ( '_' == $valuet{0} )
				        continue;
				    //echo $key . " => " . $value . "<br />";
				    //$the_keys[] = $value;
				    $custom_field = get_post_custom($s->ID);
				    echo "<p>".$value.": ".$custom_field[$value][0]."</p>";
				}
			}
			?>
			</section>
		</div>
		<?
		ob_end_flush();
	}//end main_options_page
	//view_instructor
	
	/**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function view_instructor(){
		global $wpdb;
		$page_base = $this->page_url();
		ob_start();
		?>
		<div class="wp_lms settings">
			<h1>View Student Information</h1>
			<?
			$args = array(
				'sort_column' => 'menu_order',
				'post_type' => 'instructor',
				'post_status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC'
			);
			$page_query = new WP_Query();
			$all_pages = $page_query->query( $args );
			foreach( $all_pages as $k => $s ){ 
				?>
				<h2><?= $s->post_title; ?></h2>
				<p>Currently Teaching: </p>
				<?
				$custom_field_keys = get_post_custom_keys($s->ID);
				foreach ( $custom_field_keys as $key => $value ) {
				    $valuet = trim($value);
				    if ( '_' == $valuet{0} )
				        continue;
				    //echo $key . " => " . $value . "<br />";
				    // $the_keys[] = $value;
				    $custom_field = get_post_custom($s->ID);
				    echo "<p>".$value.": ".$custom_field[$value][0]."<p>";
				}
			}
			?>

		</div>
		<?
		ob_end_flush();
	}//end main_options_page

	/**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function view_enrollment(){
		$page_base = $this->page_url();
		ob_start();
		?>
		<div class="wp_lms settings">
			<h1>View Student Information</h1>
			<?
			$args = array(
				'sort_column' => 'menu_order',
				'post_type' => 'course',
				//"course_assigned_num" => $class_num,
				//'instructor_assigned_num' => $instructor_num,
				'post_status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC'
			);
			echo "<pre>";print_r($args);echo "</pre>";
			$pages = get_pages( $args );
			echo "<pre>";print_r($pages);echo "</pre>";
			foreach( $pages as $k => $s ){ 
				?>
				<h2><?= $s->post_title; ?></h2>
				<p>Enrollment: </p>
				<?
				$custom_field_keys = get_post_custom_keys($s->ID);
				echo "<pre>";print_r($custom_field_keys);echo "</pre>";
				foreach ( $custom_field_keys as $key => $value ) {
				    $valuet = trim($value);
				    if ( '_' == $valuet{0} )
				        continue;
				    //echo $key . " => " . $value . "<br />";
				    //$the_keys[] = $value;
				    $custom_field = get_post_custom($s->ID);
				    echo "<p>".$value.": ".$custom_field[$value][0]."<p>";
				}
			}
			?>

		</div>
		<?
		ob_end_flush();
	}//end main_options_page

	/**
     *  Load scripts and styles only on Plugin pages
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @param $hook  
     **/
	public function student_settings() {
		$page_base = $this->page_url();
		ob_start();
		?>
		<div class="wp_lms settings">
			<h1>Student Settings</h1>

		</div>
		<?php
		ob_end_flush();
	}

    /**
     *  Load scripts and styles only on Plugin pages
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @param $hook  
     **/
	public function admin_scripts($hook) {
        $doReturn = true;
        $page = '';
		//prevent scripts and styles from being added to other pages
	 	foreach( $this->menu_pages as $k => $v ){
            if( $hook == $v ){
                $doReturn = false;
                $page = $k;
                break;
            }
            else if($hook != $v){
                $doReturn = true;
            }
        }
        if( $doReturn ) {
            return;
        }
        //add stuff for this page only. Will most likely change to switch() if more pages added
        if( $this->menu_pages[$page] == $this->menu_pages['main'] ) {
        	//wp_enqueue_style( 'wp-lms-admin-styles', plugins_url('css/autoup-admin.css', dirname(__FILE__).'/'.$this->plugin_folder) );
        	//wp_enqueue_script( 'sagallery-admin-plugins', plugins_url('js/plugins-admin.js', dirname(__FILE__).'/'.$this->plugin_folder ), '2603104', true  );
        }
	}//end admin_scripts
}

/**
 *  Calls WP Dashboard Object
 *  @since 1.0.0
 **/
$wp_lms_admin = new wp_lms_admin();
?>