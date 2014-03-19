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
      switch($create){

        /**
         *  
         *  @since 0.0.1
        **/

        case 'select':
          //ob_start();
        	
        	if($post->post_type == 'student_directory') { 
        		$course_count = get_post_meta($post->ID, "_enroll_count", true);
        		if( empty($course_count) ) $course_count = 1;
        		?>
        		<input type="hidden" name="<?= $type; ?>meta_noncename" id="<?= $type; ?>meta_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
        		<?
        		for($i=0;$i<$course_count;$i++){
        			$course = get_post_meta($post->ID, '_'.$type.$i, true);
	          ?>
	          <label for="_<?= $type.$i; ?>"> 
	              <?php //echo $course; ?>
	              <?= $name; ?>
	          </label>
	          <p></p>
	          <select name="_<?= $type.$i; ?>" class="widefat">
	            <?php foreach( $all_pages as $k => $p ) { ?>
	              <? $current = "";
	                if( isset( $course ) && $course == $p->ID ) $current = " selected";
	                else if( isset( $_GET[$type] ) && $_GET[$type] == $p->ID ) $current = " selected";
	              ?>
	              <option value="<?php echo $p->ID; ?>"<?php echo $current; ?>><?php echo $p->post_title; ?></option>
	            <?php } ?>
	          </select>
	          <?
          	}
        	}
        	else {
        	$course = get_post_meta($post->ID, '_'.$type, true);
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
        	}
          //ob_end_flush();
          break;

        /**
         *  
         *  @since 0.0.1
        **/

        case 'link':
        if($post->post_type == "assignment") {
          $term_array = $this->tax_names['assignment'];
          $prefix = "assignment";
        }
        else if($post->post_type == "lecture") {
          $term_array = $this->tax_names['lecture'];
          $prefix = "lecture";
        }
        for($i=0;$i<2;$i++){
          $term =  $term_array[$i].$prefix;
          $terms = wp_get_post_terms($post->ID, $term);
          $var[] = $term;
          if( !empty($terms) ) {
            foreach ($terms as $termid) { 
              ${$term} = $termid->term_id;
            }
          }
          if( $var[0] == "_course_name".$prefix && empty( $$var[0] ) ) $course_label = " Not Set";
          if( $var[1] == "_instructor_name".$prefix  && empty( $$var[1] ) ) $instructor_label = " Not Set";
        }
        if( empty( $$var[1] ) && empty( $$var[0] ) ) {
           $link = "#";
           $link_name = "Post Not Published";
        }
        else {
          $link = "edit.php?s&post_status=all&post_type=".$type."&action=-1&m=0&_course_name_$prefix=".$$var[0]."&_instructor_name_$prefix=".$$var[1]."&paged=1&action2=-1";
          $link_name = $name;
          if(!empty( $$var[0] ) ) $course_label = get_term($$var[0], '_course_name_'.$prefix)->name;
          if(!empty( $$var[1] ) ) $instructor_label = get_term($$var[1], '_instructor_name_'.$prefix)->name;
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
         *  
         *  @since 0.0.1
        **/
        case 'date':
        $m_labels = array('01','02','03','04','05','06','07','08','09','10','11','12');
        $m_values = array('2','3','4','5','6','7', '8','9','10','11','12','13','14');
        $get_meta = array('begin_month' => "_".$type."_date_begin_month", 'begin_day' => "_".$type."_date_begin_day",'begin_year' =>"_".$type."_date_begin_year",'end_month' => "_".$type."_date_end_month",'end_day' => "_".$type."_date_end_day",'end_year' => "_".$type."_date_end_year");
        foreach($get_meta as $var => $meta){
       		$$var = get_post_meta($post->ID, $meta, true);
       	}
        ?>
       <input type="hidden" name="<?= $type; ?>begin_noncename" id="<?= $type; ?>begin_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
        <div id="" class="hide-if-js" style="display: block;">
          <div class="timestamp-wrap">
            <p>
              <label for="_<?= $type; ?>_date_begin_month">
                Begin Date
              </label>
            </p>
          <select id="" name="_<?= $type; ?>_date_begin_month">
          	<?php 
          	if( empty($begin_month) ) $begin_month = date("m");
          	foreach($m_labels as $k => $n){ 
          		$selected = "";
          		if($n == $begin_month) {
          			$selected = " selected";
          		}
          	?>
            <option value="<?php echo $n; ?>"<?php echo $selected; ?>><?php echo $n."-".date('M', mktime(0, 0, 0, $m_values[$k], 0, 0) ); ?></option>
            <? } ?>
          </select> 
         	<?
         	if( empty( $begin_day ) ) $begin_day = date("j");
         	?>
          <input type="text" name="_<?= $type; ?>_date_begin_day" value="<?= $begin_day; ?>" size="2" maxlength="2" autocomplete="off">, 
          <?
          if( empty( $begin_year ) ) $begin_year = date("Y");
          ?>
          <input type="text" id="" name="_<?= $type; ?>_date_begin_year" value="<?= $begin_year; ?>" size="4" maxlength="4" autocomplete="off">
         </div>
         <div class="timestamp-wrap">
            <p>
              <label for="_<?= $type; ?>_date_end_month">
                End Date
              </label>
            </p>
          <select id="" name="_<?= $type; ?>_date_end_month">
          	<?php 
          	if( empty($end_month) ) $end_month = date("m");
          	foreach($m_labels as $k => $n){ 
          		$selected = "";
          		if($n == $end_month) {
          			$selected = " selected";
          		}
          	?>
            <option value="<?php echo $n; ?>"<?php echo $selected; ?>><?php echo $n."-".date('M', mktime(0, 0, 0, $m_values[$k], 0, 0) ); ?></option>
            <? } ?>
          </select> 
          <?
         	if( empty( $end_day ) ) $end_day = date("j")+7;
         	?>
          <input type="text" name="_<?= $type; ?>_date_end_day" value="<?= $end_day; ?>" size="2" maxlength="2" autocomplete="off">, 
          <?
          if( empty( $end_year ) ) $end_year = date("Y");
          ?>
          <input type="text" id="" name="_<?= $type; ?>_date_end_year" value="<?= $end_year;?>" size="4" maxlength="4" autocomplete="off">
         </div>
         <p>
        <strong>Days</strong>
      </p>
      <label class="screen-reader-text">Days</label>
      <div class="timestamp-wrap">
      	<?
      	$get_checkboxes = array('day_sun' => "_".$type."_day_sun", 'day_mon' => "_".$type."_day_mon", 'day_tues' => "_".$type."_day_tues",'day_wedn' => "_".$type."_day_wedn", 'day_thurs' => "_".$type."_day_thurs", 'day_fri' => "_".$type."_day_fri", 'day_sat' => "_".$type."_day_sat");
      	foreach($get_checkboxes as $var => $meta) {
      		$$var = get_post_meta($post->ID, $meta, true);
      		$checkbox_names[] = $var;
      		//echo $meta." ".$$var."<br>";
      		if( !empty($$var) ) $set_checkboxes[$var] = $$var;
      	}
      	$ch_values = array('1', '2', '3', '4', '5', '6', '7');
        $ch_labels = array('S', 'M', 'T', 'W', 'R', 'F', 'S');
        ?>
        <style type="text/css">
        	.checkbox_label {
        		padding-left:8px;
        		/*text-align: center;*/
        	}
        	.input_checkbox {
        		text-align: center;
        	}
        </style>
        <table style="width:100%;">
        	<thead>
        	<tr>
        <?
        //echo "<pre>"; print_r($set_checkboxes);echo "</pre>";
        foreach($ch_labels as $k => $ch) {
      	?>
        	<td class="checkbox_label"><label for="<?= '_'.$type.'_'.$checkbox_names[$k]; ?>"><?= $ch; ?></label></td>
        <? } ?>
	      	</tr>
	      </thead>
	      	<tbody>
	      	<tr>
       <? foreach($ch_labels as $k => $ch) {
        	$checked = "";
        	if( !empty( $set_checkboxes[$checkbox_names[$k]] ) && $set_checkboxes[$checkbox_names[$k]] == $ch_values[$k]) $checked = " checked";
      	?>
          <td class="input_checkbox"><input type="checkbox" name="<?= '_'.$type.'_'.$checkbox_names[$k]; ?>" value="<?= $ch_values[$k]; ?>"<?= $checked; ?>></td>
        <? } ?>
      		</tr>
      		</tbody>
      	</table>
      </div>
      <p>
        <strong>Begin Time</strong>
      </p>
      <label class="screen-reader-text">Begin Time</label>
      <div class="timestamp-wrap">
    	  <?php
    	  $get_times = array('begin_hour' => '_'.$type.'_begin_hour', 'begin_min' => '_'.$type.'_begin_min', 'begin_ofday' => '_'.$type.'_begin_ofday','end_hour' => '_'.$type.'_end_hour', 'end_min' => '_'.$type.'_end_min', 'end_ofday' => '_'.$type.'_end_ofday');
    	  $time_of_day = array('am', 'pm');
    	  foreach($get_times as $var => $meta) {
    	  	$$var = get_post_meta($post->ID, $meta, true);
    	  }
       	if( empty( $begin_hour ) ) $begin_hour = date("g");
       	?>
        <input type="text" name="_<?= $type; ?>_begin_hour" value="<?= $begin_hour; ?>" size="2" maxlength="2" autocomplete="off"> : 
        <?php
       	if( empty( $begin_min ) ) $begin_min = date("i");
       	?>
        <input type="text" name="_<?= $type; ?>_begin_min" value="<?= $begin_min; ?>" size="2" maxlength="2" autocomplete="off">

        <select name="_<?= $type; ?>_begin_ofday">
        	<?
        	foreach ($time_of_day as $key => $tod) {  
        		$selected = "";
  					if( $tod == $begin_ofday ) $selected = " selected";
        	?>
          <option value="<?= $tod; ?>"<?= $selected; ?>><?= strtoupper($tod); ?></option>
          <?
        	}
          ?>
        </select>
      </div>
      <div class="timestamp-wrap">
        <p>
          <strong>End Time</strong>
        </p>
        <label class="screen-reader-text">End Time</label>
        <?php
         	if( empty( $end_hour ) ) $end_hour = date("g")+3;
       	?>
        <input type="text" name="_<?= $type; ?>_end_hour" value="<?= $end_hour; ?>" size="2" maxlength="2" autocomplete="off"> : 
        <?php
       	if( empty( $end_min ) ) $end_min = date("i");
       	?>
        <input type="text" name="_<?= $type; ?>_end_min" value="<?= $end_min; ?>" size="2" maxlength="2" autocomplete="off">
        <select name="_<?= $type; ?>_end_ofday">
					<?
        	foreach ($time_of_day as $key => $tod) {  
        		$selected = "";
  					if( $tod == $end_ofday ) $selected = " selected";
        	?>
          <option value="<?= $tod; ?>"<?= $selected; ?>><?= strtoupper($tod); ?></option>
          <?
        	}
          ?>
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

        case "number":
        	$enrollment_count = get_post_meta($post->ID, "_enroll_count", true);
        	$statuses = array("inactive", "active");
          ?>
          <input type="hidden" name="<?= $type; ?>_enrollment_noncename" id="<?= $type; ?>status_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
          <p>
            <label for="_enroll_count">
            	<? //echo $status; ?>
              <?= $name; ?>
            </label>
          </p>
          <?
          if( empty($enrollment_count) ) $enrollment_count = 1;
          ?>
          <input type="number" name="_enroll_count" value="<?= $enrollment_count; ?>"> 
          <?
          break;
          case "status":
        	$status = get_post_meta($post->ID, "_status", true);
        	$statuses = array("inactive", "active");
          ?>
          <input type="hidden" name="<?= $type; ?>status_noncename" id="<?= $type; ?>status_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
          <p>
            <label for="_status">
            	<? //echo $status; ?>
              <?= $name; ?>
            </label>
          </p>
          <select name="_status">
          	<? 
          	foreach($statuses as $k => $s){ 
          		$selected = "";
          		if( $status == $s ) $selected = " selected";
          	?>
            <option value="<?= $s; ?>"<?= $selected; ?>><?= ucfirst($s); ?></option>
            <? 
          	} 
          	?>
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
      $type = $post->post_type;
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
    		if( array_key_exists($v, $_POST) ){
	      	$events_meta[$v] = $_POST[$v];
	      	//$events_meta['_instructor'] = $_POST['_instructor'];
      	}
      	// else {
      	// 	echo $v." is not in the array of POST<br>";
      	// }
      }
     	// echo "<pre>";print_r($events_meta);echo "</pre>";
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
	        	if($post->post_type == 'student_directory'){
	        		$course_count = get_post_meta($post->ID, "_enroll_count", true);
	        		if(empty( $course_count )) $course_count = 1;
	        		for($i=0;$i<$course_count;$i++){
	        			update_post_meta($post->ID, $key, $value);
	        		}
	        	}
	        	else {
	          	update_post_meta($post->ID, $key, $value);
	        	}
	          if($key == "_status") {
	          	wp_set_object_terms( $post->ID, $value, 'course_status' );
	          }
	          if($key == "_instructor" && $post->post_type == 'course') {
	          	wp_set_object_terms( $post->ID, get_the_title($value), 'course_instructor_name' );
	          }
	          if( $key == "_course"  && $post->post_type == "lecture" ) {
	            wp_set_object_terms( $post->ID, $value, 'course_num' );
	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_name_'.$type );
	          }
	          if( $key == "_instructor" && $post->post_type == "lecture"  ) {
	            wp_set_object_terms( $post->ID, $value, $key.'_num_'.$type );
	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_name_'.$type );
	          }
	          if( $key == "_course"  && $post->post_type == "assignment" ) {
	            wp_set_object_terms( $post->ID, $value, $key.'_num_'.$type );
	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_name_'.$type );
	          }
	          if( $key == "_instructor" && $post->post_type == "assignment"  ) {
	            wp_set_object_terms( $post->ID, $value, $key.'_num_'.$type );
	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_name_'.$type );
	          }
	        }
	        else { 
	        // If the custom field doesn't have a value
	        	if($post->post_type == 'student_directory'){
	        		$course_count = get_post_meta($post->ID, "_enroll_count", true);
	        		if(empty( $course_count )) $course_count = 1;
	        		for($i=0;$i<$course_count;$i++){
	        			add_post_meta($post->ID, $key, $value);
	        		}
	        	}
	        	else {
	          	add_post_meta($post->ID, $key, $value);
	        	}
	          if($key == "_status" && $post->post_type == 'course') {
	          	wp_set_object_terms( $post->ID, $value, 'course_status' );
	          }
	          if($key == "_instructor" && $post->post_type == 'course') {
	          	wp_set_object_terms( $post->ID, get_the_title($value), 'course_instructor_name' );
	          }
	          if( $key == "_course"  && $post->post_type == "lecture" ) {
	            wp_set_object_terms( $post->ID, $value, $key.'_num_'.$type );
	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_name_'.$type );
	          }
	          if( $key == "_instructor" && $post->post_type == "lecture"  ) {
	            wp_set_object_terms( $post->ID, $value, $key.'_num_'.$type );
	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_name_'.$type ); 
	          }
	          if( $key == "_course"  && $post->post_type == "assignment" ) {
	            wp_set_object_terms( $post->ID, $value, $key.'_num_'.$type );
	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_name_'.$type );
	          }
	          if( $key == "_instructor" && $post->post_type == "assignment"  ) {
	            wp_set_object_terms( $post->ID, $value, $key.'_num_'.$type );
	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_name_'.$type ); 
	          }
	        }
	        if( empty($value) ) {
	        	delete_post_meta($post->ID, $key);
	        }
	        if( !$value ) {
	        	delete_post_meta($post->ID, $key);
	        }
	        foreach($this->postdataname as $k => $v) {
	        	if( !array_key_exists($v, $events_meta) ) delete_post_meta($post->ID, $v);
	        }
	        // Delete if blank
	      }//end foreach

    	} //end if is_array
    	// else {
    	// 	echo "something has gone wrong";
    	// 	echo "<pre>";print_r($_POST);echo "</pre>";
    	// }
    }

    // public function add_assignment_columns( $columns ) {
    //   return array_merge($columns, 
    //             array('course_assigned' => __('Course'),
    //                 'instructor' => __('Instructor')
    //             )
    //         );
    // }
    // public function custom_assignment_column( $column, $id ) {
    //     global $post;
    //     $type = $post->post_type;
    //     switch ( $column ) {
    //       case 'course_assigned':
    //       $post_meta = get_post_meta( $post->ID , '_course' , true );
    //         echo '<a href="edit.php?post_type='.$type.'&amp;_course='.$post_meta.'" title="'.get_the_title( $post_meta ).'">'.get_the_title( $post_meta ).'</a>';
    //         break;
    //       case 'instructor':
    //       $post_meta = get_post_meta( $post->ID , '_instructor' , true );
    //         echo '<a href="edit.php?post_type='.$type.'&amp;_instructor='.$post_meta.'" title="'.get_the_title( $post_meta ).'">'.get_the_title( $post_meta ).'</a>';
    //         break;
    //       default:
    //       	//hmm nope nothing here
    //       	break;
    //     }
    // }

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