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
        $this->filters_post = array('assignment', 'assignment');
        $this->filters_tax = array('course_name', 'instructor_name');
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
      $all_pages = $page_query->query( array( 'post_type' => $metabox['args']['type'], 'posts_per_page' => -1, 'orderby' => 'title',
        'order' => 'ASC' ) );
      $course = get_post_meta($post->ID, '_'.$metabox['args']['type'], true);
      switch($create){
          case 'select':
            ob_start();
      ?>
      <label for="_<?= $type; ?>"> 
          <?= $course; ?>         
          <?= $metabox['args']['name']; ?>
      </label>
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
          ob_end_flush();
          break;
        case 'link':
        $postCategories = get_the_category($post->ID);
        for($i=0;$i<2;$i++){
          foreach ( $postCategories as $postCategory ) {
            if($i==0){
              $courseid = get_term_by('id', $postCategory->cat_ID, 'course_name');
            }
            else if($i==1){
            $instructorid = get_term_by('id', $postCategory->cat_ID, 'course_name');
            }
          }
        }
        // $courseid = get_term_by('id', $postCategories[0]->cat_ID, 'course_name');
        // $instructorid = get_term_by('id', $postCategories[0]->cat_ID, 'instructor_name');
        $link = "edit.php?s&post_status=all&post_type=".$type."&action=-1&m=0&course_name=".$courseid."&instructor_name=".$instructorid."&paged=1&action2=-1"
        ?>
        <label class="screen-reader-text" for="_<?= $create; ?>">
          <?= $name; ?>
        </label>
        <p>
          <a href="<?= $link; ?>" title="<?= $name; ?>"><?= $name; ?></a>
        </p>
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
          if( $key == "_course" ) {
            wp_set_object_terms( $post->ID, $value, 'course_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'course_name' );
          }
          if( $key == "_instructor" ) {
            wp_set_object_terms( $post->ID, $value, 'instructor_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'instructor_name' ); 
          }
        } 
        else { 
        // If the custom field doesn't have a value
          add_post_meta($post->ID, $key, $value);
          if( $key == "_course" ) {
            wp_set_object_terms( $post->ID, $value, 'course_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'course_name' );
          }
          if( $key == "_instructor" ) {
            wp_set_object_terms( $post->ID, $value, 'instructor_num' );
            wp_set_object_terms( $post->ID, get_the_title($value), 'instructor_name' ); 
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
    $post_type = $this->filters_post;
    $taxonomy = $this->filters_tax;
    foreach($post_type as $k => $p){
      if ($typenow == $post_type[$k]) {
        $selected = isset($_GET[$taxonomy[$k]]) ? $_GET[$taxonomy[$k]] : '';
        $info_taxonomy = get_taxonomy($taxonomy[$k]);
        wp_dropdown_categories(array(
          'show_option_all' => __("Show All {$info_taxonomy->label}"),
          'taxonomy' => $taxonomy[$k],
          'name' => $taxonomy[$k],
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
    $post_type = $this->filters_post;
    $taxonomy = $this->filters_tax;
    $q_vars = &$query->query_vars;
    foreach($post_type as $k => $p){
      if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type[$k] && isset($q_vars[$taxonomy[$k]]) && is_numeric($q_vars[$taxonomy[$k]]) && $q_vars[$taxonomy[$k]] != 0) {
        $term = get_term_by('id', $q_vars[$taxonomy[$k]], $taxonomy[$k]);
        $q_vars[$taxonomy[$k]] = $term->slug;
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