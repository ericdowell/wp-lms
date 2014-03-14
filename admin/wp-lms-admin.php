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
		$this->menu_pages['main'] = add_menu_page( $this::$plugin_name, 'GrandPubbah', 'manage_options', $this::$plugin_name, array( $this, 'main_options_page' ) );
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		$this->menu_pages['schedule'] = add_submenu_page( "edit.php?post_type=course", 'WP LMS Schedule', 'Schedule', 'manage_options', $this::$plugin_name.'_schedule', array( $this,"schedule_page" ), $this->plugin_img_url.'png/24/schedule.png' );
		$this->menu_pages['assignments'] = add_submenu_page( "edit.php?post_type=course", 'WP LMS Assignments', 'Assignments', 'manage_options', $this::$plugin_name.'_assign', array( $this,"assignment_page" ) );
		$this->menu_pages['lectures'] = add_submenu_page( "edit.php?post_type=course", 'WP LMS Lectures', 'Lectures', 'manage_options', $this::$plugin_name.'_lecture', array( $this,"lecture_page" ) );
		$this->menu_pages['settings'] = add_submenu_page( "edit.php?post_type=course", 'WP LMS Settings', 'Settings', 'manage_options', $this::$plugin_name.'_settings', array( $this,"settings_page" ) );
		//edit.php?post_type=student_directory
		$this->menu_pages['view-student'] = add_submenu_page( "edit.php?post_type=student_directory", 'WP LMS View Students', 'View Students', 'manage_options', $this::$plugin_name.'_view_student', array( $this,"view_student" ) );
		$this->menu_pages['view-instructors'] = add_submenu_page( "edit.php?post_type=instructor", 'WP LMS View Instrustors', 'View Instrustors', 'manage_options', $this::$plugin_name.'_view_instructor', array( $this,"view_instructor" ) );
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	}//end admin_settings
	static public function page_url(){
		//post_type=course&page=wp_lms_assign
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
		<div id="poststuff" class="wp_lms settings">
		<div id="post-body" class="columns-2">
			<h1>Schedule</h1>
			<style type="text/css">
				#poststuff #post-body.columns-2 #postbox-container-1 {
					float: left;
					/*margin-right: -300px;*/
					width: 280px;
				}
				.wp_lms .js .postbox .hndle {
					cursor: pointer;
				}
			</style>
			<form id="postbox-container-1" action="<?= $page_base; ?>" method="post">
			<?php
			wp_lms_html_gen::list_select( 'instructor', 'Instructors', "POST", $_POST );
			wp_lms_html_gen::list_select( 'course', 'Courses', "POST", $_POST );
			wp_lms_html_gen::date_set( 'Dates Active', 'no-time' );
        	?>
			
			</form>
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
			<?php //echo do_shortcode( '[searchandfilter taxonomies="course_assigned_name"]' ); ?>
  			<? wp_lms_html_gen::form_open(array("post_type" => "assignment", "course_assigned_num" => "566", "showposts" => "10"), $page_base ) ?>
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
			<h2>Pages</h2>
			<?
			foreach( $this->menu_pages as $k => $v ) {
			?>
				<p><?= $k; ?> <?= $v; ?></p>
			<?
			}
			$class_num = "568";
			$instructor_num = "560";
			$the_post_type = 'lecture';
			$args = array(
				'sort_column' => 'menu_order',
				'post_type' => $the_post_type,
				"course_assigned_num" => $class_num,
				'instructor_assigned_num' => $instructor_num,
				'post_status' => 'publish'
			);
			$pages = get_pages( $args );
			$page_query = new WP_Query();
			$all_pages = $page_query->query( array( 'post_type' => $the_post_type, 'post_status' => 'publish', 'posts_per_page' => -1 ) );
			$parent_count = 0;
			foreach( $pages as $k => $p ){ 
				if( $p->post_parent == 0 && has_term( $class_num, 'course_assigned_num', $p->ID ) && has_term( $instructor_num, 'instructor_assigned_num', $p->ID ) ) {
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
				//"course_assigned_num" => $class_num,
				//'instructor_assigned_num' => $instructor_num,
				'post_status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC'
			);
			$pages = get_pages( $args );
			foreach( $pages as $k => $s ){ 
				?>
				<h2><?= $s->post_title; ?></h2>
				<p>Enrollment: </p>
				<?
				$custom_field_keys = get_post_custom_keys($s->ID);
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
	//view_instructor
	
	/**
     *  Options Page for Plugin
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @return HTML settings page
     **/
	public function view_instructor(){
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
			$pages = get_pages( $args );
			foreach( $pages as $k => $s ){ 
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
     *  Load scripts and styles only on Plugin pages
     *  @since 1.0.0
     *  @updated 1.0.0
     *  @param $hook  
     **/
	

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
        	wp_enqueue_style( 'autoup-admin-styles', plugins_url('css/autoup-admin.css', dirname(__FILE__).'/'.$this->plugin_folder) );
        	wp_enqueue_script( 'sagallery-admin-plugins', plugins_url('js/plugins-admin.js', dirname(__FILE__).'/'.$this->plugin_folder ), '2603104', true  );
        }
	}//end admin_scripts
}

/**
 *  Calls WP Dashboard Object
 *  @since 1.0.0
 **/
$wp_lms_admin = new wp_lms_admin();
?>