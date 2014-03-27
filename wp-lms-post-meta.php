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
      $post_type = $post->post_type;
      $pid = $post->ID;
      $name = $metabox['args']['name'];
      $create = $metabox['args']['create'];
      $nonce = $metabox['args']['noncename'];
      $page_query = new WP_Query();
      $all_pages = $page_query->query( array( 'post_type' => $type, '_status' => 'active', 'posts_per_page' => -1, 'orderby' => 'title',
        'order' => 'ASC' ) );
      switch($create){

        /**
         *  
         *  @since 0.0.1
        **/
        case 'select':       
          $course = get_post_meta($post->ID, '_'.$type, true); 	
          $status = get_post_meta($post->ID, '_status', true);  
        	if($post_type == 'student_directory') {
        		//$course_count = get_post_meta($post->ID, "_enroll_count", true);
        		//if( empty($course_count) ) $course_count = 1;
            $course = explode(",",$course);
        		?>
        		<input type="hidden" name="<?= $type; ?>meta_noncename" id="<?= $type; ?>meta_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
	          <label for="_<?= $type.$i; ?>[]"> 
	              <?php //echo "<pre>";print_r($course);echo "</pre>"; ?>
	              <?= $name; ?>
	          </label>
	          <p></p>
	          <select multiple name="_<?= $type.$i; ?>[]" class="widefat">
	            <?php 
              usort( $all_pages, array($this, 'sort_post_title') );
              foreach( $all_pages as $k => $p ) { ?>
	              <? 
                if($p->post_parent == 0){
                  $current = "";
	                if( isset( $course ) && is_array($course) && in_array($p->ID, $course) ) $current = " selected";
                  if( isset( $course ) && $course == $p->ID ) $current = " selected";
	                else if( isset( $_GET[$type] ) && $_GET[$type] == $p->ID ) $current = " selected";
	              ?>
	              <option value="<?php echo $p->ID; ?>"<?php echo $current; ?>><?php echo $p->post_title; ?></option>
	             <?php 
                }
              } ?>
	          </select>
	          <?
        	}
          else if($type == "instructor" && $post_type == "assignment" || $type == "instructor" && $post_type == "lecture") {
            $ins = explode(",", $course);
            ?>
            <label for="_<?= $type; ?>"> 
                <?php //echo $course; ?>
                <?= $name; ?>
            </label>
            <p></p>
            <input type="hidden" name="<?= $type; ?>meta_noncename" id="<?= $type; ?>meta_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
            <select multiple name="_<?= $type; ?>[]" class="widefat">
              <?php 
              usort( $all_pages, array($this, 'sort_post_title') );
              foreach( $all_pages as $k => $p ) {
                  $pid = $p->ID;
                  if($p->post_parent == 0){ 
                    $current = "";
                    if( isset( $ins ) && in_array($pid, $ins)  ) $current = " selected";
                    else if( isset( $_GET[$type] ) && $_GET[$type] == $p->ID ) $current = " selected";
                ?>
                <option value="<?php echo $p->ID; ?>"<?php echo $current; ?>><?php echo $p->post_title; ?></option>
              <?php }
              } ?>
            </select>
            <?
          }
        	else if($type == 'instructor' && isset($status) && strstr($status, "active") || $type != 'instructor' ) {
          	?>
            <label for="_<?= $type; ?>"> 
                <?php //echo $course; ?>
                <?= $name; ?>
            </label>
            <p></p>
            <input type="hidden" name="<?= $type; ?>meta_noncename" id="<?= $type; ?>meta_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
            <select name="_<?= $type; ?>" class="widefat">
              <?php 
              usort( $all_pages, array($this, 'sort_post_title') );
              foreach( $all_pages as $k => $p ) { ?>
                <?
                  if($p->post_parent == 0){ 
                    $current = "";
                    if( isset( $course ) && $course == $p->ID ) $current = " selected";
                    else if( isset( $_GET[$type] ) && $_GET[$type] == $p->ID ) $current = " selected";
                ?>
                <option value="<?php echo $p->ID; ?>"<?php echo $current; ?>><?php echo $p->post_title; ?></option>
              <?php }
              } ?>
            </select>
            <?
        	}
          else if( $type == 'instructor' && isset($status) && !strstr($status, "active") ) {
            ?>
            <input type="hidden" name="<?= $type; ?>meta_noncename" id="<?= $type; ?>meta_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
             <label for="_<?= $type; ?>"> 
                Inherited By Parent
            </label>
            <input type="hidden" name="_<?= $type; ?>" value="inherited">
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
            $course_label = " Not Set";
            $instructor_label = " Not Set";
            if( isset($var[0]) && $var[0] == "_course_name".$prefix && empty( $$var[0] ) ) $course_label = " Not Set";
            if( isset($var[1]) && $var[1] == "_instructor_name".$prefix  && empty( $$var[1] ) ) $instructor_label = " Not Set";
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
          $post_status = get_post_meta($post->ID, "_status", true);
          if($post_status != "timeline" && $post_status != "assignments"){
            $m_labels = array('01','02','03','04','05','06','07','08','09','10','11','12');
            $m_values = array('2','3','4','5','6','7', '8','9','10','11','12','13','14');
            $get_date_meta = array('begin_month' => "_".$type."_d_begin_month", 'begin_day' => "_".$type."_d_begin_day",'begin_year' =>"_".$type."_d_begin_year",'end_month' => "_".$type."_d_end_month",'end_day' => "_".$type."_d_end_day",'end_year' => "_".$type."_d_end_year");
            foreach($get_date_meta as $var => $meta){
           		$$var = get_post_meta($post->ID, $meta, true);
           	}
            ?>
           <input type="hidden" name="<?= $type; ?>begin_noncename" id="<?= $type; ?>begin_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
            <div id="" class="hide-if-js" style="display: block;">
              <div class="timestamp-wrap">
                <p>
                  <label for="_<?= $type; ?>_d_begin_month">
                    Begin Date
                  </label>
                </p>
              <select id="" name="_<?= $type; ?>_d_begin_month">
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
              <input type="text" required name="_<?= $type; ?>_d_begin_day" value="<?= $begin_day; ?>" size="2" maxlength="2" autocomplete="off">, 
              <?
              if( empty( $begin_year ) ) $begin_year = date("Y");
              ?>
              <input type="text" required name="_<?= $type; ?>_d_begin_year" value="<?= $begin_year; ?>" size="4" maxlength="4" autocomplete="off">
             </div>
             <div class="timestamp-wrap">
                <p>
                  <label for="_<?= $type; ?>_d_end_month">
                    End Date
                  </label>
                </p>
              <select id="" name="_<?= $type; ?>_d_end_month">
              	<?php 
              	if( empty($end_month) ) $end_month = date("m", mktime(0,0,0,$begin_month,$begin_day+7,$begin_year) );
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
             	if( empty( $end_day ) ) $end_day = date("j", mktime(0,0,0,$begin_month,$begin_day+7,$begin_year) );
             	?>
              <input type="text" required name="_<?= $type; ?>_d_end_day" value="<?= $end_day; ?>" size="2" maxlength="2" autocomplete="off">, 
              <?
              if( empty( $end_year ) ) $end_year = date("Y");
              ?>
              <input type="text" required name="_<?= $type; ?>_d_end_year" value="<?= $end_year;?>" size="4" maxlength="4" autocomplete="off">
             </div>
             <p>
            <strong>Days</strong>
          </p>
          <label class="screen-reader-text">Days</label>
          <div class="timestamp-wrap">
          	<?
          	//$get_checkboxes = array('day_sun' => "_".$type."_day_sun", 'day_mon' => "_".$type."_day_mon", 'day_tues' => "_".$type."_day_tues",'day_wedn' => "_".$type."_day_wedn", 'day_thurs' => "_".$type."_day_thurs", 'day_fri' => "_".$type."_day_fri", 'day_sat' => "_".$type."_day_sat");
          	//foreach($get_checkboxes as $var => $meta) {
          		//$$var = get_post_meta($post->ID, $meta, true);
          	//	$checkbox_names[] = $var;
          		//echo $meta." ".$$var."<br>";
          		//if( !empty($$var) ) $set_checkboxes[$var] = $$var;
          	//}
            $course_days = get_post_meta($post->ID, "_course_days", true);
            //echo $course_days." From Post<br>";
            if( isset($course_days) && !empty($course_days) ) $course_days = explode(",", $course_days);
            //echo $course_days." After Explode?<br>";
           // echo "<pre>";print_r($course_days);echo "</pre>";
          	$ch_values = array('0', '1', '2', '3', '4', '5', '6');
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
            <table style="width:100% max-width:254px;">
            	<thead>
            	<tr>
            <?
            //echo "<pre>"; print_r($set_checkboxes);echo "</pre>";
            foreach($ch_labels as $k => $ch) {
          	?>
            	<td class="checkbox_label"><label for="_course_days[]"><?= $ch; ?></label></td>
            <? } ?>
    	      	</tr>
    	      </thead>
    	      	<tbody>
    	      	<tr>
           <? foreach($ch_labels as $k => $ch) {
            	$checked = "";
            	if( is_array($course_days) && in_array($ch_values[$k], $course_days) ) $checked = " checked";
              if( !is_array($course_days) && $course_days == $ch_values[$k] ) $checked = " checked";
          	?>
              <td class="input_checkbox"><input type="checkbox" name="_course_days[]" value="<?= $ch_values[$k]; ?>"<?= $checked; ?>></td>
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
          }
          else {
          ?>
            <p>Not needed.</p>
          <?
          }
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


          case 'assign_prop':
            $assignment_type = get_post_meta($post->ID, "_assign_type", true);
            $points = get_post_meta($post->ID, "_points", true);
            $comps = get_post_meta($post->ID, "_competencies", true);
            $_class_start = get_post_meta($post->ID, "_class_start", true);
            $_class_due = get_post_meta($post->ID, "_class_due", true);
            $est_time = get_post_meta($post->ID, "_est_time", true);
            $turn_type = get_post_meta($post->ID, "_turn_type", true);
            $applies = get_post_meta($post->ID, "_applies_to", true);
            ?>
            <input type="hidden" name="<?php echo $nonce; ?>" id="<?php echo $nonce; ?>" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
            <?
            $assign_types = array('assignment', 'information');
            ?>
            <p><label for="_assign_type">Assignment Type:</label><br>
              <select name="_assign_type">
                <?
                foreach($assign_types as $k => $type){
                  $selected = "";
                  if($type == $assignment_type) $selected = " selected";
                ?>
                <option value="<?= $type; ?>"<?= $selected; ?>><?= ucfirst($type); ?></option>
                <?
                }
                ?>
              </select>
            </p>
            <?
            if($assignment_type == 'assignment'){
            ?>
            <p><label for="_points">Point Possible</label><br>
            <input type="text" name="_points" value="<?= $points; ?>"></p>
            <p><label for="_competencies">Competencies</label><br>
            <input type="text" name="_competencies" value="<?= $comps; ?>"></p>
            <?
            $start_end = array("_class_start" => "Class to Start Working", "_class_due" => "Class Due");
            foreach($start_end as $name => $label) {
            ?>
            <p><label for="<?= $name; ?>"><?= $label; ?></label><br>
              <select name="<?= $name; ?>">
            <?
              foreach(range(1, 11) as $num) {
                $cfirst = "";
                $csec = "";
                $class = explode(".", $$name);
                if( isset($class) && is_array($class) && $class[0] == $num ){ 
                  //echo "true";
                  if($class[1] == '1') { $cfirst = ' selected'; }
                  else if($class[1] == '2') { $csec = ' selected'; }
                }
                ?>
                <option value="<?= $num; ?>.1"<?= $cfirst; ?>><?= $num; ?>.1</option>
                <option value="<?= $num; ?>.2"<?= $csec; ?>><?= $num; ?>.2</option>
                <?
              }
            ?>
              </select>
            </p>
            <?
            }
            $tval = "";
            $tmeasure = "";
            if( isset($est_time) && strstr($est_time, ",") ) $est_time = explode(",", $est_time);
            if( is_array($est_time) && isset($est_time[0]) ) $tval = $est_time[0];
            if( is_array($est_time) && isset($est_time[1]) ) $tmeasure = $est_time[1];
            ?>
            <p><label for="_est_time">Estimated Time</label><br>
              <input type="text" name="_est_time[]" value="<?= $tval; ?>">
              <select name="_est_time[]">
                <?
                $t_types = array("minutes", "hours");
                foreach( $t_types as $v ) {
                  $t_select = "";
                  if( isset($tmeasure) && $v == $tmeasure) {
                    $t_select = " selected";
                  }
                ?>
                  <option value="<?= $v; ?>"<?= $t_select; ?>><?= ucfirst($v); ?></option>
                <?
                }
                ?>
              </select>
            </p>
            <?
            $turn = array("subdomain" => "Subdomain", "email" => "Email", "drop_off" => "Drop Off", "shared" => "Shared");
            ?>
            <p><label for="_turn_type">Turn in Type</label><br>
              <select name="_turn_type">
                <?
                foreach($turn as $name => $lab){
                  $selected = '';
                  if($turn_type == $name) {
                    $selected = ' selected';
                  }
                ?>
                <option value="<?= $name; ?>"<?= $selected; ?>><?= $lab; ?></option>
                <?
                }
                ?>
              </select>
            </p>
            <? 
            $ins = get_post_meta($post->ID, "_instructor", true);
            $cour = get_post_meta($post->ID, "_course", true);
            ?>
            <p><label for="_applies_to">Applies to</label><br>
              <select name="_applies_to">            
                <option value="none">N/A</option>
                <?
                if( !empty($ins) || !empty($cour)) {
                  $page_query = new WP_Query();
                  $args = array(
                    'post_type' => 'assignment',
                    'post_status' => 'publish',
                    '_instructor_nu_assignment' => $ins,
                    '_course_nu_assignment' => $cour,
                    'orderby' => 'title',
                    'order' => 'ASC'
                  );
                  $assigns = $page_query->query( $args );
                  foreach($assigns as $k => $a) {
                    $aid = $a->ID;
                    $aname = $a->post_title;
                    $selected = "";
                    if(isset($applies) && $applies == $aid){
                      $selected = " selected";
                    }
                    ?>
                  <option value="<?= $aid; ?>"<?= $selected; ?>><?=$aname; ?></option>
                    <?
                  }
                }
                ?>
              </select>
            </p>
            <?
            }
            else if($assignment_type == 'information') {
              ?>
              <p>Properties aren't needed.</p>
              <?
            }
            break;


          case "status":
        	$status = get_post_meta($post->ID, "_status", true);
        	$statuses = array("inactive", "active", "timeline", "assignments");
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
      if( !empty($post) || isset( $_POST['_wpnonce'] ) && !wp_verify_nonce( $_POST['_wpnonce'], plugin_basename(__FILE__) ) )  {
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
          //changes any invalid date to a correct date
          if( strstr($v, "_month") && strstr($v, "_course") ) {
              $month_l = $this->postdataname[$k];
              $day_l = $this->postdataname[$k+1];
              $year_l = $this->postdataname[$k+2];
              $month_v = $_POST[$this->postdataname[$k]];
              $day_v = $_POST[$this->postdataname[$k+1]];
              $year_v = $_POST[$this->postdataname[$k+2]];
              $_POST[$month_l] = date("m", mktime(0,0,0,$month_v,$day_v,$year_v) );
              $_POST[$day_l] = date("j", mktime(0,0,0,$mont_v,$day_v,$year_v) );
              $_POST[$year_l] = date("Y", mktime(0,0,0,$month_v,$day_v,$year_v) );
          }
	      	$events_meta[$v] = $_POST[$v];
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

          // if($key == "_course_days") {
          //   echo $value." before <br>";
          // }

	        $value = implode(',', (array)$value); 
	        // If $value is an array, make it a CSV (unlikely)

          // if($key == "_course_days") {
          //   echo $value." after <br>";
          // }
          // If the custom field already has a value
	        if(get_post_meta($post->ID, $key, FALSE)) { 
              // if($key == "_course_days") {
              //   echo $value." inside already value <br>";
              // }
  	        	if($post->post_type == 'student_directory'){
                //should create taxomony for courses enrolled
  	        	}
  	        	else {
  	          	update_post_meta($post->ID, $key, $value);
  	        	}
              //updates course days term nu/na
              if($key == "_course_days"){
                wp_set_object_terms( $post->ID, $value, 'course_days_nu' );
                $value = explode(",", $value);
                foreach( $value as $dkey => $dnum ) {
                  $dnames[] = $this->get_day_sname($dnum);
                }
                $value = implode(",", $dnames);
                wp_set_object_terms( $post->ID, $value, 'course_days_na' ); 
              }
              //updates course status term
  	          if($key == "_status") {
  	          	wp_set_object_terms( $post->ID, $value, 'course_status' );
  	          }
              //updates course instructor term
  	          if($key == "_instructor" && $post->post_type == 'course') {
                if($value != "inherited"){
  	          	  wp_set_object_terms( $post->ID, get_the_title($value), 'course_instructor_na' );
                  //query and replace all active instructor if there is a change
                  // $query = new WP_Query();
                  // $assign_pages = $query->query(

                  //  );
                }
                else {
                   wp_set_object_terms( $post->ID, $value, 'course_instructor_na' );
                }
  	          }
              //updates lecture course term
  	          if( $key == "_course"  && $post->post_type == "lecture" ) {
  	            wp_set_object_terms( $post->ID, $value, 'course_nu' );
  	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_na_'.$type );
  	          }
              //updates lecture instructor term
  	          if( $key == "_instructor" && $post->post_type == "lecture"  ) {
  	            wp_set_object_terms( $post->ID, $value, $key.'_nu_'.$type );
  	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_na_'.$type );
  	          }
              //updates assignment course term
  	          if( $key == "_course"  && $post->post_type == "assignment" ) {
  	            wp_set_object_terms( $post->ID, $value, $key.'_nu_'.$type );
  	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_na_'.$type );
  	          }
              //updates assignment instructor term
  	          if( $key == "_instructor" && $post->post_type == "assignment"  ) {
                $course = get_post_meta($post->ID, '_course', true);
                //checks to see if the active instructor for course is assigned to assignment
                if( !empty($course) ){
                  $active_ins = get_post_meta($course, '_instructor', true);
                  if( strstr($course, ",") ) $active_array = explode(",", $course);
                  wp_set_object_terms( $post->ID, get_the_title($active_ins), $key.'_na_assignment_active' );
                  wp_set_object_terms( $post->ID, $active_ins, $key.'_nu_assignment_active' );
                }
                else if( empty($course) ) {
                  $active_ins = get_post_meta($events_meta["_course"], '_instructor', true);
                  if( strstr($events_meta["_course"], ",") ) $active_array = explode(",", $events_meta["_course"]);
                  wp_set_object_terms( $post->ID, get_the_title($active_ins), $key.'_na_assignment_active' );
                  wp_set_object_terms( $post->ID, $active_ins, $key.'_nu_assignment_active' ); 
                }
  	            wp_set_object_terms( $post->ID, $value, $key.'_nu_'.$type );
  	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_na_'.$type );
  	          }
	        }
          // If the custom field doesn't have a value
	        else { 
  	        	if($post->post_type == 'student_directory'){

  	        	}
  	        	else {
  	          	add_post_meta($post->ID, $key, $value);
  	        	}
              if($key == "_course_days"){
                wp_set_object_terms( $post->ID, $value, 'course_days_nu' );
                $value = explode(",", $value);
                foreach( $value as $dkey => $dnum ) {
                  $dnames[] = $this->get_day_sname($dnum);
                }
                $value = implode(",", $dnames);
                wp_set_object_terms( $post->ID, $value, 'course_days_na' ); 
              }
  	          if($key == "_status" && $post->post_type == 'course') {
  	          	wp_set_object_terms( $post->ID, $value, 'course_status' );
  	          }
  	          if($key == "_instructor" && $post->post_type == 'course') {
  	          	if($value != "inherited"){
                  wp_set_object_terms( $post->ID, get_the_title($value), 'course_instructor_na' );
                }
                else {
                   wp_set_object_terms( $post->ID, $value, 'course_instructor_na' );
                }
  	          }
  	          if( $key == "_course"  && $post->post_type == "lecture" ) {
  	            wp_set_object_terms( $post->ID, $value, $key.'_nu_'.$type );
  	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_na_'.$type );
  	          }
  	          if( $key == "_instructor" && $post->post_type == "lecture"  ) {
  	            wp_set_object_terms( $post->ID, $value, $key.'_nu_'.$type );
  	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_na_'.$type ); 
  	          }
  	          if( $key == "_course"  && $post->post_type == "assignment" ) {
  	            wp_set_object_terms( $post->ID, $value, $key.'_nu_'.$type );
  	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_na_'.$type );
  	          }
  	          if( $key == "_instructor" && $post->post_type == "assignment"  ) {
                $course = get_post_meta($post->ID, '_course', true);
                if( !empty($course) ){
                  $active_ins = get_post_meta($course, '_instructor', true);
                  if( strstr($course, ",") ) $active_array = explode(",", $course);
                  wp_set_object_terms( $post->ID, get_the_title($active_ins), $key.'_na_assignment_active' );
                  wp_set_object_terms( $post->ID, $active_ins, $key.'_nu_assignment_active' );
                }
                else if( empty($course) ) {
                  $active_ins = get_post_meta($events_meta["_course"], '_instructor', true);
                  if( strstr($events_meta["_course"], ",") ) $active_array = explode(",", $events_meta["_course"]);
                  wp_set_object_terms( $post->ID, get_the_title($active_ins), $key.'_na_assignment_active' );
                  wp_set_object_terms( $post->ID, $active_ins, $key.'_nu_assignment_active' ); 
                }
  	            wp_set_object_terms( $post->ID, $value, $key.'_nu_'.$type );
  	            wp_set_object_terms( $post->ID, get_the_title($value), $key.'_na_'.$type ); 
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