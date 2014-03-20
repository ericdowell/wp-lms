<?php
/*
Plugin Name: WP LMS
Plugin URI: http://ericdowell.com/wp/plugins/
Description: Learning management system for wordpress.
Author: Eric Dowell
Version: 1.0.0
Author URI: http://ericdowell.com
*/

/**
 *  Plugin Object
 *  @since 1.0.0
 *  @updated 1.0.0
 **/
class wp_lms {
    static $version = '1.0.0';
    static $name = 'WP LMS';
    static $plugin_name = 'wp_lms';

    /**
     *  Sets object varables and calls functions
     *  @since 1.0.0
     **/
    public function __construct() {
        $this->menu_pages = array();
        $this->plugin_folder = explode( '/', plugin_basename( __FILE__ ) )[0];
        $this->plugin_base_url = plugin_dir_url( __FILE__ );
        $this->plugin_admin_url = $this->plugin_base_url . 'admin/';
        $this->plugin_img_url = $this->plugin_base_url . 'img/';
        $this->plugin_dir_long = dirname( __FILE__ );
        $this->plugin_inc_dir = $this->plugin_dir_long . '/inc/';
        $this->set_aval = array("admin_option_pages", "version" => $this::$version, "custom_post_type_names", "settings_page_options", "post_meta");
        $this->tax_names = array('assignment' => array('_course_name_', '_instructor_name_'), 'lecture' => array('_course_name_', '_instructor_name_') );
        

        //run on this (parent)
        if( !get_parent_class( $this ) ) {
            include('wp-lms-post-meta.php');
            include('wp-lms-admin-bar.php');
            include('wp-lms-post-types.php');
            // add_filter('post_type_link', array( $this, 'filter_post_links'), 1, 2);
            add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );
            //scripts and styles
            add_action('wp_enqueue_scripts',array($this, 'styles_scripts') );
            add_action( 'wp_header', array($this, 'add_to_header' ) );
            add_action( 'wp_footer', array($this, 'footer_scripts' ) );
            
            //filters work though!
            add_action('restrict_manage_posts', array($this,'restrict_assign_by_course') );
            add_filter('parse_query', array($this,'convert_id_to_term_in_query') );
            //widget class
            include('wp-lms-widgets.php');
            add_action( 'widgets_init', array($this, 'create_widgets') );
            
            //heler functions will go here
            include('wp-lms-helpers.php');
            //includes all admin options
            if( is_admin() ) include('admin/wp-lms-admin.php'); // Global name $wp_lms_admin
        }

        //run within admin class
        if( is_admin() && get_parent_class( $this ) &&  get_class( $this ) == "wp_lms_post_types"  ) {
            add_action( 'init', array($this, 'post_types' ) );
        }



        //run within admin class
        if( is_admin() && get_parent_class( $this ) &&  get_class( $this ) == "wp_lms_admin"  ) {
            include('admin/classes/wp-lms-admin-html.php'); // Global name $wp_lms_html_gen
            add_action( 'admin_menu', array( $this,'admin_settings' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        }


        if( is_admin() && get_parent_class( $this ) &&  get_class( $this ) == "wp_lms_widgets"  ) {

        }


        //run within html gen class
        if( is_admin() && get_parent_class( $this ) &&  get_class( $this ) == "wp_lms_html_gen"  ) {

        }

        //run within admin bar class
        if( is_admin() && get_parent_class( $this ) && get_class( $this ) == "wp_lms_adminbar" ) {
          add_action( 'wp_before_admin_bar_render', array($this, 'content_menu' ) );
        }


        //run within post meta class
        if( is_admin() && get_parent_class( $this ) && get_class($this) == "wp_lms_post_meta" ) {
          $this->noncename = array('coursemeta_noncename', 'instructormeta_noncename', 'course_enrollment_noncename', 'coursebegin_noncename', 'sessionweeks_noncename', 'coursestatus_noncename');
          $this->postdataname = array('_course', '_instructor', '_status', '_enroll_count', '_course_date_begin_month', '_course_date_begin_day', '_course_date_end_month', '_course_date_end_day','_course_day_sun', '_course_day_mon', '_course_day_tues', '_course_day_wedn', '_course_day_thurs', '_course_day_fri', '_course_day_sat', '_course_begin_hour', '_course_begin_min', '_course_end_hour', '_course_end_min', '_course_end_ofday', '_course_begin_ofday');
          //for enrollment use in student directory custom post type
          for($i=0;$i<10;$i++){
            $this->postdataname[] = "_course".$i;
          }
          $this->post_metabox = array(
            array('wp_lms_course_list_assign','Course List', 'assignment', 'side','high', array('name' => "Courses", 'type' => 'course', 'create' => 'select', 'noncename' => 'coursemeta_noncename') ),
            array('wp_lms_enrollment_count_student','# of Course(s) Enrolled', 'student_directory', 'side','high', array('name' => "# of Course(s) Enrolled", 'type' => 'course', 'create' => 'number', 'noncename' => 'course_enrollment_noncename') ),
            array('wp_lms_course_list_student','Course Enrollment', 'student_directory', 'side','high', array('name' => "Courses", 'type' => 'course', 'create' => 'select', 'noncename' => 'coursemeta_noncename') ), 
            array('wp_lms_course_list_lecture', 'Course List', 'lecture', 'side', 'high', array( 'name' => "Courses", 'type' => 'course', 'create' => 'select', 'noncename' => 'coursemeta_noncename') ), 
            array('wp_lms_instructor_list_assign', 'Instructor List', 'assignment', 'side', 'high', array( 'name' => "Instructors", 'type' => 'instructor', 'create' => 'select', 'noncename' => 'instructormeta_noncename') ),
            array('wp_lms_instructor_list_lecture', 'Instructor List', 'lecture', 'side', 'high', array( 'name' => "Instructors", 'type' => 'instructor', 'create' => 'select', 'noncename' => 'instructormeta_noncename') ),
            array('wp_lms_link_to_type_assign', 'Go back to Assignments', 'assignment', 'side', 'high', array( 'name' => "Go Back to Assignemnts", 'type' => 'assignment', 'create' => 'link', 'noncename' => '') ),
            array('wp_lms_link_to_type_lecture', 'Go back to Lectures', 'lecture', 'side', 'high', array( 'name' => "Go Back to Lectures", 'type' => 'lecture', 'create' => 'link', 'noncename' => '') ),
            array('wp_lms_course_status', 'Course Status', 'course', 'side', 'high', array( 'name' => "Status", 'type' => 'course', 'create' => 'status', 'noncename' => 'coursestatus_noncename') ),
            array('wp_lms_course_time', 'Course Schedule', 'course', 'side', 'high', array( 'name' => "Course Schedule", 'type' => 'course', 'create' => 'date', 'noncename' => 'coursebegin_noncename') ),
            array('wp_lms_session_weeks', 'Weeks Active', 'session', 'side', 'high', array( 'name' => "Weeks Active", 'type' => 'session', 'create' => 'weeks', 'noncename' => 'sessionweeks_noncename') ),
            array('wp_lms_instructor_list_course', 'Instructor List', 'course', 'side', 'high', array( 'name' => "Instructors", 'type' => 'instructor', 'create' => 'select', 'noncename' => 'instructormeta_noncename') ) 
          );
          add_action( 'add_meta_boxes', array( $this, 'post_metaboxes' ) );
          add_action('save_post', array( $this, 'save_post_meta') );

          //add_filter( 'manage_assignment_posts_columns', array( $this ,'add_assignment_columns' ) );
          //add_action( 'manage_assignment_posts_custom_column' , array($this,'custom_assignment_column'), 10, 2 );
          //sorting not working yet
          // add_filter('manage_edit-assignment_sortable_columns', array( $this, 'assignment_sortable_columns' ) );
          // add_filter('requests', array( $this , 'handle_assignment_column_sorting' ) );
        }


    }//end construct

    /**
     *  Called on activation, calls defaults
     *  @since 1.0.0
     **/
    public function install() {
        $this->set_defaults();
    }//end install

    static public function check_option( $val ) {
      $option = get_option( $val );
      if( empty( $option ) ) return true;
      else return false; 
    }

    public function styles_scripts() {
      wp_enqueue_style( 'wp-lms-menu-styles', plugins_url('inc/ml-push-menu/component.css', dirname(__FILE__).'/'.$this->plugin_folder) );
      wp_enqueue_script( 'wp-lms-menu-js', plugins_url('inc/ml-push-menu/modernizr.custom.js', dirname(__FILE__).'/'.$this->plugin_folder ), '2603104', true  );
     // wp_enqueue_script( 'wp-lms-menu-js', plugins_url('inc/ml-push-menu/mlpushmenu.js', dirname(__FILE__).'/'.$this->plugin_folder ), '2603104', true  );
     // wp_enqueue_script( 'wp-lms-menu-js', plugins_url('inc/ml-push-menu/classie.js', dirname(__FILE__).'/'.$this->plugin_folder ), '2603104', true  );
    }

    public function add_to_header() {
      ?>
      <script src="<?= $this->plugin_base_url.'inc/ml-push-menu/modernizr.custom.js'; ?>"></script>
      <?php
    }

    public function footer_scripts() {
      ?>
      <script src="<?= $this->plugin_base_url.'inc/ml-push-menu/classie.js'; ?>"></script>
      <script src="<?= $this->plugin_base_url.'inc/ml-push-menu/mlpushmenu.js'; ?>"></script>
      <script>
        new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ), {
            type : 'cover'
        } );
      </script>
      <?
    }

    /**
     *  Sets Defaults in option table
     *  @since 1.0.0
     *  @updated 1.0.0
     **/
    public function set_defaults($set = "all") {
      //schedule stuff and settings page options
      switch($set) {
        case 'admin_option_pages':

          break;
        case 'version':
          update_option($this::$plugin_name."_version", $this::$version);
          if( $this->check_option( $this::$plugin_name."_version" ) ) {
              add_option( $this::$plugin_name."_version", $this::$version);
          }
          break;
        case 'custom_post_type_names':

          break;
        case "settings_page_options":

          break;
        case "post_meta":
          
          //add_option($plugin_name."_something", "");
          break;
        case "all":
          if( $this->check_option( $this::$plugin_name."_version" ) ) {
              add_option($this::$plugin_name."_version", $this::$version);
          }
          update_option($this::$plugin_name."_version", $this::$version);
          break;
        default:
          //nothing to do here
          break;
      }
        //store stuff in database
        // update_option($plugin_name."_version", $version);
        // add_option($plugin_name."_something", "");
    }//end defaults

    /**
     *  Custom action links
     *  @link http://www.wpmods.com/adding-plugin-action-links
     *  @since 0.0.1
     */
    public function action_links($links, $file) {
        static $this_plugin;

        if ( !$this_plugin ) {
            $this_plugin = plugin_basename( __FILE__ );
        }
        // check to make sure we are on the correct plugin
        if ( $file == $this_plugin ) {
            // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
            $settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page='.$this::$plugin_name.'">'.__( 'Settings', $this::$plugin_name ).'</a>';
            // add the link to the list
            array_unshift($links, $settings_link);
        }
        return $links;
    }//end action_links
 

    public function create_widgets() {
       register_widget( 'wp_lms_widgets' );
    }

    public function filter_post_links($url, $post) {
      if ( 'assignment' == get_post_type( $post ) || 'lecture' == get_post_type( $post ) ) {
        $course = get_post_meta($post->ID, '_course', true);
        $instructor = get_post_meta($post->ID, '_instructor', true);
        if( empty($course) ) {
          $course = "pick_course";
        }
        else {
          $course = get_post($course)->post_name;
        }
        if( empty($instructor) ) {
          $instructor = "pick_instructor";
        }
        else {
          $instructor = get_post($instructor)->post_name;
          $instructor = explode("-", $instructor)[1];
          $instructor = strtolower($instructor);
        }
        if( 'assignment' == get_post_type( $post ) ) {
          //flush_rewrite_rules();
          return str_replace('%assign%', "a-".$instructor."-".$course, $url);
        }
        if( 'lecture' == get_post_type( $post ) ) {
          //flush_rewrite_rules();
          return str_replace('%lect%', "l-".$instructor."-".$course, $url);
        }
      }
      return $url;
    }


    public function restrict_assign_by_course() {
      global $typenow;
      if(isset($_GET['post_type'])){
        $type = $_GET['post_type'];
      }
      if($type == "assignment" ) {
        $post_type = 'assignment';
        $taxonomy = $this->tax_names['assignment'];
      }
      if($type == "lecture" ) {
        $post_type = 'lecture';
        $taxonomy = $this->tax_names['lecture'];
      }
      if( empty($taxonomy) ) {
        return;
      }
      foreach($taxonomy as $k => $tax){
        if ($typenow == $post_type ) {
          $post_name = get_post($tax);
          $selected = isset($_GET[$tax.$post_type]) ? $_GET[$tax.$post_type] : '';
          $info_taxonomy = get_taxonomy($tax.$post_type);
          wp_dropdown_categories(array(
            'show_option_all' => __("Show All {$info_taxonomy->label}"),
            'taxonomy' => $tax.$post_type,
            'name' => $tax.$post_type,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
          ));
        }
      }
  }


  public function convert_id_to_term_in_query($query) {
    global $pagenow;
    if(isset($_GET['post_type'])){
      $type = $_GET['post_type'];
    }
    if($type == "assignment" ) {
      $post_type = 'assignment';
      $taxonomy = $this->tax_names['assignment'];
    }
    if($type == "lecture" ) {
      $post_type = 'lecture';
      $taxonomy = $this->tax_names['lecture'];
    }
    if( empty($taxonomy) ) {
      return;
    }
    $q_vars = &$query->query_vars;
    foreach($taxonomy as $k => $tax){
      if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$tax.$post_type]) && is_numeric($q_vars[$tax.$post_type]) && $q_vars[$tax.$post_type] != 0) {
        $term = get_term_by('id', $q_vars[$tax.$post_type], $tax.$post_type);
        $q_vars[$tax.$post_type] = $term->slug;
      }
    }
  }

}//end Object

/**
 *  Calls object install on plugin activation
 *  @since 0.0.1
 **/
register_activation_hook( dirname(__FILE__) , array( 'wp_lms', 'install' ) );

/**
 *  Calls Plugin Object
 *  @since 0.0.1
 **/
$wp_lms_plugin = new wp_lms();

?>