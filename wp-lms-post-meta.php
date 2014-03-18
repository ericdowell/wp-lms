<?php

class wp_lms_post_meta extends wp_lms {
	
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
              <?php echo $course; ?>
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
        <?php
        /*
        <p><strong>OR</strong></p>
        <p>
          <a href="edit.php?post_type=course&amp;page=wp_lms_<?= $type; ?>" title="Go Back List of Courses">Go Back List of Courses</a>
        </p>
        */
          break;

        /**
         *  Calls object install on plugin activation
         *  @since 0.0.1
        **/
        case 'date':
        $d_values = array('01','02','03','04','05','06','07','08','09','10','11','12');
        $c_values = array();
        $c_lables = array();
        ?>
       <input type="hidden" name="<?= $type; ?>begin_noncename" id="<?= $type; ?>begin_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
        <div id="" class="hide-if-js" style="display: block;">
          <div class="timestamp-wrap">
            <p>
              <label for="_<?= $type; ?>_date_begin_month">
                Begin Date <?= date('M',mktime(0, 0, 0, 03, 0, 0) ); ?>
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
          <input type="text" name="_<?= $type; ?>_date_begin_day" value="15" size="2" maxlength="2" autocomplete="off">, <input type="text" id="" name="_date_begin_year" value="2014" size="4" maxlength="4" autocomplete="off">
         </div>
         <div class="timestamp-wrap">
            <p>
              <label for="_<?= $type; ?>_date_end_month">
                End Date
              </label>
            </p>
          <select id="" name="_<?= $type; ?>_date_end_month">
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
          <input type="text" name="_<?= $type; ?>_date_end_day" value="15" size="2" maxlength="2" autocomplete="off">, <input type="text" id="" name="_date_end_year" value="2014" size="4" maxlength="4" autocomplete="off">
         </div>
         <p>
        <strong>Days</strong>
      </p>
      <label class="screen-reader-text">Days</label>
      <div class="timestamp-wrap">
        <label for="sun">S
          <input type="checkbox" name="_<?= $type; ?>_day_sun" value="0">
        </label>
        <label for="mon">M
          <input type="checkbox" name="_<?= $type; ?>_day_mon" value="1">
        </label>
        <label for="tues">T
          <input type="checkbox" name="_<?= $type; ?>_day_tues" value="2">
        </label>
        <label for="wedn">W
          <input type="checkbox" name="_<?= $type; ?>_day_wedn" value="3">
        </label>
        <label for="thurs">R
          <input type="checkbox" name="_<?= $type; ?>_day_thurs" value="4">
        </label>
        <label for="fri">F
          <input type="checkbox" name="_<?= $type; ?>_day_fri" value="5">
        </label>
        <label for="sat">S
          <input type="checkbox" name="_<?= $type; ?>_day_sat" value="6">
        </label>
      </div>
      <p>
        <strong>Begin Time</strong>
      </p>
      <label class="screen-reader-text">Begin Time</label>
      <div class="timestamp-wrap">
        <input type="text" name="_<?= $type; ?>_begin_hr" value="10" size="2" maxlength="2" autocomplete="off"> : 
        <input type="text" name="_<?= $type; ?>_begin_min" value="42" size="2" maxlength="2" autocomplete="off">
        <select name="_<?= $type; ?>_begin_ofday">
          <option value="am">AM</option>
          <option value="pm">PM</option>
        </select>
      </div>
      <div class="timestamp-wrap">
        <p>
          <strong>End Time</strong>
        </p>
        <label class="screen-reader-text">End Time</label>
        <input type="text" name="_coures_end_hr" value="10" size="2" maxlength="2" autocomplete="off"> : 
        <input type="text" name="_coures_end_min" value="42" size="2" maxlength="2" autocomplete="off">
        <select name="_<?= $type; ?>_end_ofday">
          <option value="am">AM</option>
          <option value="pm">PM</option>
        </select>
      </div>
        </div>
        <?
          break;


        case 'weeks':
        ?>
        <input type="hidden" name="<?= $type; ?>weeks_noncename" id="<?= $type; ?>length_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
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

        case "status":
          ?>
          <input type="hidden" name="<?= $type; ?>status_noncename" id="<?= $type; ?>status_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
          <p>
            <label for="_status">
              <?= $name; ?>
            </label>
          </p>
          <select name="_status">
            <option value="inactive">Inactive</option>
            <option value="active">Active</option>
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
      $doReturn = true;
      foreach( $this->noncename as $k => $v ) {
        if( wp_verify_nonce( $_POST[$v], plugin_basename(__FILE__) ) ) {
          $doReturn = false;
          break;
        }
      }
      if ( $doReturn ) {
        return $post->ID;
      }
      // Is the user allowed to edit the post or page?
      if ( !current_user_can( 'edit_post', $post->ID ) )
        return $post->ID;

      // OK, we're authenticated: we need to find and save the data
      // We'll put it into an array to make it easier to loop though.
    	foreach( $this->postdataname as $k => $v ) {
    		if( in_array($v, $_POST) ){
	      	$events_meta[$v] = $_POST[$v];
	      	//$events_meta['_instructor'] = $_POST['_instructor'];
      	}
      }
      if(is_array($events_meta) ) {
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
	          /*if( $key == "_course"  && $post->post_type == "lecture" ) {
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
	          } */
	        } 

	        else { 
	        // If the custom field doesn't have a value
	          add_post_meta($post->ID, $key, $value);
	          /*
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
	          */
	        }
	        if(!$value) delete_post_meta($post->ID, $key); 
	        // Delete if blank
	      }//end foreach
    	} //end if is_array
    }

    public function add_assignment_columns( $columns ) {
      return array_merge($columns, 
                array('course_assigned' => __('Course'),
                    'instructor' => __('Instructor')
                )
            );
    }
    function custom_assignment_column( $column, $id ) {
        global $post;
        $type = $post->post_type;
        switch ( $column ) {
          case 'course_assigned':
          $post_meta = get_post_meta( $post->ID , '_course' , true );
            echo '<a href="edit.php?post_type='.$type.'&amp;_course='.$post_meta.'" title="'.get_the_title( $post_meta ).'">'.get_the_title( $post_meta ).'</a>';
            break;
          case 'instructor':
          $post_meta = get_post_meta( $post->ID , '_instructor' , true );
            echo '<a href="edit.php?post_type='.$type.'&amp;_instructor='.$post_meta.'" title="'.get_the_title( $post_meta ).'">'.get_the_title( $post_meta ).'</a>';
            break;
          default:
          	//hmm nope nothing here
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

}

$wp_lms_post_meta = new wp_lms_post_meta();