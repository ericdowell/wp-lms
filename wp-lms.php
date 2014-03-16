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
        // $this->filters_post_a = array('assignment', 'assignment');
        // $this->filters_tax_a = array('course_name', 'instructor_name');
        // $this->filters_post_l = array('lecture', 'lecture');
        // $this->filters_tax_l = array('course_name', 'instructor_name');
        $this->tax_names = array('assignment' => array('course_name_a', 'instructor_name_a'), 'lecture' => array('course_name_l', 'instructor_name_l') );
        $this->post_metabox = array(array(
                            'wp_lms_course_list_assign',
                            'Course List', 
                            'assignment', 
                            'side',
                            'high', 
                            array('name' => "Courses", 'type' => 'course', 'create' => 'select') 
                           ), array(
                            'wp_lms_course_list_lecture', 
                            'Course List', 
                            'lecture', 
                            'side', 
                            'high', 
                            array( 'name' => "Courses", 'type' => 'course', 'create' => 'select') 
                           ), array(
                            'wp_lms_instructor_list_assign', 
                            'Instructor List', 
                            'assignment', 
                            'side', 
                            'high', 
                            array( 'name' => "Instructors", 'type' => 'instructor', 'create' => 'select') 
                           ),
                           array(
                            'wp_lms_instructor_list_lecture', 
                            'Instructor List', 
                            'lecture', 
                            'side', 
                            'high', 
                            array( 'name' => "Instructors", 'type' => 'instructor', 'create' => 'select') 
                           ),
                           array(
                            'wp_lms_link_to_type_assign', 
                            'Go back to Assignments', 
                            'assignment', 
                            'side', 
                            'high', 
                            array( 'name' => "Go Back to Assignemnts", 'type' => 'assignment', 'create' => 'link') 
                           ),
                          array(
                            'wp_lms_link_to_type_lecture', 
                            'Go back to Lectures', 
                            'lecture', 
                            'side', 
                            'high', 
                            array( 'name' => "Go Back to Lectures", 'type' => 'lecture', 'create' => 'link') 
                          ),
                          array(
                            'wp_lms_session_begin', 
                            'Session Begins On', 
                            'session', 
                            'side', 
                            'high', 
                            array( 'name' => "Session Begins On", 'type' => 'session', 'create' => 'date') 
                          ),
                          array(
                            'wp_lms_session_weeks', 
                            'Weeks Active', 
                            'session', 
                            'side', 
                            'high', 
                            array( 'name' => "Weeks Active", 'type' => 'session', 'create' => 'weeks') 
                          ) );
        //run on this
        if( !get_parent_class( $this ) ) {
            add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );
            add_action( 'init', array($this, 'post_types' ) );
            add_action( 'add_meta_boxes', array( $this, 'post_metaboxes' ) );
            add_action('save_post', array( $this, 'save_post_meta') );
            add_filter( 'manage_assignment_posts_columns', array($this,'add_assignment_columns') );
            add_action( 'manage_assignment_posts_custom_column' , array($this,'custom_assignment_column'), 10, 2 );
            //sorting not working yet
            // add_filter('manage_edit-assignment_sortable_columns', array($this, 'assignment_sortable_columns') );
            // add_filter('requests', array($this, 'handle_assignment_column_sorting') );
            //filters work though!
            add_action('restrict_manage_posts', array($this,'restrict_assign_by_course') );
            add_filter('parse_query', array($this,'convert_id_to_term_in_query') );

            include('wp-lms-helpers.php');
            //includes all admin options
            if( is_admin() ) include('admin/wp-lms-admin.php'); // Global name $wp_lms_admin
        }
        //run within admin class
        if( is_admin() && get_parent_class( $this ) && strstr( get_class( $this ), "wp_lms_admin" ) ) {
            include('admin/classes/wp-lms-admin-html.php'); // Global name $wp_lms_html_gen
            add_action( 'admin_menu', array( $this,'admin_settings' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        }
        //run within html gen class
        if( is_admin() && get_parent_class( $this ) && strstr( get_class( $this ), "wp_lms_html_gen" ) ) {

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

    public function post_types() {

    }

    public function post_metaboxes() {
      foreach($this->post_metabox as $k => $v) {
        add_meta_box($v[0], 
                  $v[1], 
                  array($this, 'create_metabox'), 
                  $v[2], 
                  $v[3], 
                  $v[4], 
                  $v[5]
                  );
      }
      //add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
    }
    public function create_metabox( $post, $metabox ) {
      global $post;
      $type = $metabox['args']['type'];
      $name = $metabox['args']['name'];
      $create = $metabox['args']['create'];
      $page_query = new WP_Query();
      $all_pages = $page_query->query( array( 'post_type' => $type, 'posts_per_page' => -1, 'orderby' => 'title',
        'order' => 'ASC' ) );
      $course = get_post_meta($post->ID, '_'.$type, true);
      switch($create){

        /**
         *  Calls object install on plugin activation
         *  @since 0.0.1
        **/

        case 'select':
          //ob_start();
          ?>
          <label for="_<?= $type; ?>"> 
              <?php //echo $course; ?>         
              <?= $name; ?>
          </label>
          <p></p>
          <input type="hidden" name="<?= $type; ?>meta_noncename" id="<?= $type; ?>meta_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
          <select name="_<?= $type; ?>" class="widefat">
            <?php foreach( $all_pages as $k => $p ) { ?>
              <? $current = "";
                if( isset( $course ) && $course == $p->ID ) $current = " selected";
                else if( isset( $_GET[$type] ) && $_GET[$type] == $p->ID ) $current = " selected";
              ?>
              <option value="<?php echo $p->ID; ?>"<?php echo $current; ?>><?php echo $p->post_title; ?></option>
            <?php } ?>
          </select>
          <?
          //ob_end_flush();
          break;

        /**
         *  Calls object install on plugin activation
         *  @since 0.0.1
        **/

        case 'link':
        if($post->post_type == "assignment") {
          $term_array = $this->tax_names['assignment'];
          $prefix = "_a";
        }
        else if($post->post_type == "lecture") {
          $term_array = $this->tax_names['lecture'];
          $prefix = "_l";
        }
       // $this->terms = array('course_name','instructor_name');
        for($i=0;$i<2;$i++){
          $term =  $term_array[$i];
          $terms = wp_get_post_terms($post->ID, $term);
          $var[] = $term;
          if( !empty($terms) ) {
            foreach ($terms as $termid) { 
              ${$term} = $termid->term_id;
            }
          }
          if( $var[0] == "course_name".$prefix && empty( $$var[0] ) ) $course_label = " Not Set";
          if( $var[1] == "instructor_name".$prefix  && empty( $$var[1] ) ) $instructor_label = " Not Set";
        }
        if( empty( $$var[1] ) && empty( $$var[0] ) ) {
           $link = "#";
           $link_name = "Post Not Published";
        }
        else {
          $link = "edit.php?s&post_status=all&post_type=".$type."&action=-1&m=0&course_name$prefix=".$$var[0]."&instructor_name$prefix=".$$var[1]."&paged=1&action2=-1";
          $link_name = $name;
          if(!empty( $$var[1] ) ) $instructor_label = get_term($$var[1], 'instructor_name'.$prefix)->name;
          if(!empty( $$var[0] ) ) $course_label = get_term($$var[0], 'course_name'.$prefix)->name;
        }
        ?>
        <label class="screen-reader-text" for="_<?= $create; ?>">
          <?= $name; ?>
        </label>
        <p>
          <a href="<?= $link; ?>" title="<?= $name; ?>"><?= $link_name; ?></a>
        </p>
        <p>
          <strong>Link above will use filters below:</strong>
        </p>
        <p>
          Course Filter: <strong><?= $course_label; ?></strong><br>
          Instructor Filter: <strong><?= $instructor_label; ?></strong>
        </p>
        <p><strong>OR</strong></p>
        <p>
          <a href="edit.php?post_type=course&amp;page=wp_lms_<?= $type; ?>" title="Go Back List of Courses">Go Back List of Courses</a>
        </p>
        <?
          break;

        /**
         *  Calls object install on plugin activation
         *  @since 0.0.1
        **/
        case 'date':
        ?>
       <input type="hidden" name="<?= $type; ?>begin_noncename" id="<?= $type; ?>begin_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
        <div id="" class="hide-if-js" style="display: block;">
          <div class="timestamp-wrap">
            <p>
              <label for="mm" class="screen-reader-text">
                <?= $name; ?>
              </label>
            </p>
          <select id="" name="_<?= $type; ?>_date_begin_month">
            <option value="01">01-Jan</option>
            <option value="02">02-Feb</option>
            <option value="03" selected="selected">03-Mar</option>
            <option value="04">04-Apr</option>
            <option value="05">05-May</option>
            <option value="06">06-Jun</option>
            <option value="07">07-Jul</option>
            <option value="08">08-Aug</option>
            <option value="09">09-Sep</option>
            <option value="10">10-Oct</option>
            <option value="11">11-Nov</option>
            <option value="12">12-Dec</option>
          </select> 
          <input type="text" id="" name="_<?= $type; ?>_date_begin_day" value="15" size="2" maxlength="2" autocomplete="off">, <input type="text" id="" name="_date_begin_year" value="2014" size="4" maxlength="4" autocomplete="off">
         </div>
        </div>
        <?
          break;
        case 'weeks':
        ?>
        <input type="hidden" name="<?= $type; ?>length_noncename" id="<?= $type; ?>length_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
        <p>
        <label class="screen-reader-text">
            <?= $name; ?>
        </label>
        </p>
        <input type="text" name="_session_length" value="" size="2" maxlength="2" autocomplete="off">
        <select name="_period_measurement">
          <option name="weeks">Weeks</option>
          <option name="months">Months</option>
        </select>
        <?
          break;
        default:
          //nothing to do here yet
          break;
        }

    }

    public function save_post_meta( $post_id ) {
      global $post;
      // verify this came from the our screen and with proper authorization,
      // because save_post can be triggered at other times
      if ( !wp_verify_nonce( $_POST['coursemeta_noncename'], plugin_basename(__FILE__) ) || !wp_verify_nonce( $_POST['instructormeta_noncename'], plugin_basename(__FILE__) ) ) {
        return $post->ID;
      }
      // Is the user allowed to edit the post or page?
      if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;

      // OK, we're authenticated: we need to find and save the data
      // We'll put it into an array to make it easier to loop though.
      $events_meta['_course'] = $_POST['_course'];
      $events_meta['_instructor'] = $_POST['_instructor'];

      // Add values of $events_meta as custom fields
      foreach ($events_meta as $key => $value) { 
      // Cycle through the $events_meta array!

        if( $post->post_type == 'revision' ) return; 
        // Don't store custom data twice

        $value = implode(',', (array)$value); 
        // If $value is an array, make it a CSV (unlikely)

        if(get_post_meta($post->ID, $key, FALSE)) { 
        // If the custom field already has a value
          update_post_meta($post->ID, $key, $value);
          if( $key == "_course"  && $post->post_type == "lecture" ) {
            wp_set_object_terms( $post->ID, $value, 'course_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'course_name_l' );
          }
          if( $key == "_instructor" && $post->post_type == "lecture"  ) {
            wp_set_object_terms( $post->ID, $value, 'instructor_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'instructor_name_l' ); 
          }
          if( $key == "_course"  && $post->post_type == "assignment" ) {
            wp_set_object_terms( $post->ID, $value, 'course_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'course_name_a' );
          }
          if( $key == "_instructor" && $post->post_type == "assignment"  ) {
            wp_set_object_terms( $post->ID, $value, 'instructor_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'instructor_name_a' ); 
          }
        } 
        else { 
        // If the custom field doesn't have a value
          add_post_meta($post->ID, $key, $value);
          if( $key == "_course"  && $post->post_type == "lecture" ) {
            wp_set_object_terms( $post->ID, $value, 'course_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'course_name_l' );
          }
          if( $key == "_instructor" && $post->post_type == "lecture"  ) {
            wp_set_object_terms( $post->ID, $value, 'instructor_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'instructor_name_l' ); 
          }
          if( $key == "_course"  && $post->post_type == "assignment" ) {
            wp_set_object_terms( $post->ID, $value, 'course_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'course_name_a' );
          }
          if( $key == "_instructor" && $post->post_type == "assignment"  ) {
            wp_set_object_terms( $post->ID, $value, 'instructor_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'instructor_name_a' ); 
          }
        }
        if(!$value) delete_post_meta($post->ID, $key); 
        // Delete if blank
      }
    }

    public function add_assignment_columns( $columns ) {
      return array_merge($columns, 
                array('course_assigned' => __('Course'),
                    'instructor' => __('Instructor'),
                    'session' =>__( 'Session')
                )
            );
    }
    function custom_assignment_column( $column, $id ) {
        global $post;
        switch ( $column ) {
          case 'course_assigned':
            echo get_the_title( get_post_meta( $post->ID , '_course' , true ) );
            break;
          case 'instructor':
            echo get_the_title( get_post_meta( $post->ID , '_instructor' , true ) );
            break;
          case 'session':
            echo "-"; //get_post_meta( $post_id , 'client' , true ); 
            break;
        }
    }

    // public function assignment_sortable_columns( $columns ){
    //     $columns['course_assigned'] = 'course_assigned';
    //     $columns['session'] = 'session';
    //     return $columns;
    // }

    // public function handle_assignment_column_sorting( $vars ){
    //     $orderby = $query->get( 'orderby');
    //     if( !isset( $vars['orderby'] ) || isset( $vars['orderby'] ) && 'course_assigned' == $vars['orderby'] ){
    //       $vars = array_merge( $vars, array(
    //         'meta_key' => 'course_assigned',
    //         'orderby'  => 'meta_value'
    //       ));
    //     }
    //     if( isset($vars['orderby']) && 'session' == $vars['orderby'] ){
    //       $vars = array_merge( $vars, array(
    //         'meta_key' => 'session',
    //         'orderby'  => 'meta_value'
    //       ));
    //     }
    //     return $vars;
    // }

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
        $selected = isset($_GET[$tax]) ? $_GET[$tax] : '';
        $info_taxonomy = get_taxonomy($tax);
        wp_dropdown_categories(array(
          'show_option_all' => __("Show All {$info_taxonomy->label}"),
          'taxonomy' => $tax,
          'name' => $tax,
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
      if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$tax]) && is_numeric($q_vars[$tax]) && $q_vars[$tax] != 0) {
        $term = get_term_by('id', $q_vars[$tax], $tax);
        $q_vars[$tax] = $term->slug;
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