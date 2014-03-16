<?php
/**
 *  Admin Page Object, Child of Plugin Object
 *  @since 1.0.0
 *  @updated 1.0.0
 **/
class wp_lms_html_gen extends wp_lms {
	
	static public function gen_html( $type, $info = array() ){
		switch( $type ) {
			case "nav":

				break;
			case "form-open":

				break;

			case "form-tbody":

				break;
			case "view-info":

				break;
			case "list-select":

				break;
			default:
				return "<p>Seem there isn't a output for <strong>".$type."</strong> defined.</p>";
				break;
		}
	} //end function
	public function list_select( $type, $name, $method = "GET", $POST = "", $GET = "" ){
		$page_query = new WP_Query();
		$all_pages = $page_query->query( array( 'post_type' => $type, 'posts_per_page' => -1, 'orderby' => 'menu_order',
        'order' => 'ASC' ) );
		?>
		<div class="postbox">
			<h3 class="hndle"><span><?php echo ucfirst($type); ?> List</span></h3>
			<div class="inside">
				<p>
				<label for="wp_lms_<?= $type; ?>">
					<strong><?= $name; ?></strong>
				</label>
				</p>
				<select name="wp_lms_<?= $type; ?>" class="">
		        <?php foreach( $all_pages as $k => $p ) { ?>
		          <? $current = "";
		            //if( isset( $course ) && $course == $p->ID ) $current = " selected";
		            if( $method == "GET" && isset( $GET[$type] ) && $GET[$type] == $p->ID || $method == "POST" && isset( $POST[$type] ) && $POST[$type] == $p->ID) $current = " selected";
		          ?>
		          <option value="<?php echo $p->ID; ?>"<?php echo $current; ?>><?php echo $p->post_title; ?></option>
		        <?php } ?>
		      	</select>
	      	</div>
	      	<p></p>
  		</div>
      <?php

	}

	public function date_set( $name, $time, $POST = "" ) {
		?>
	<div id="submit" class="postbox">
	<h3 class="hndle"><span><?php echo $name; ?></span></h3>
	<div class="inside">
		<div id="scheduletimewrap" class="hide-if-js" style="display: block;">
			<p>
				<strong>Days</strong>
			</p>
			<label class="screen-reader-text">Days</label>
			<div class="timestamp-wrap">
				<label for="sun">S
					<input type="checkbox" name="sun" value="0">
				</label>
				<label for="mon">M
					<input type="checkbox" name="mon" value="1">
				</label>
				<label for="tues">T
					<input type="checkbox" name="tues" value="2">
				</label>
				<label for="wedn">W
					<input type="checkbox" name="wedn" value="3">
				</label>
				<label for="thurs">R
					<input type="checkbox" name="thurs" value="4">
				</label>
				<label for="fri">F
					<input type="checkbox" name="fri" value="5">
				</label>
				<label for="sat">S
					<input type="checkbox" name="sat" value="6">
				</label>
			</div>
			<p>
				<strong>Begin Time</strong>
			</p>
			<label class="screen-reader-text">Begin Time</label>
			<div class="timestamp-wrap">
				<input type="text" name="wp_lms_hr" value="10" size="2" maxlength="2" autocomplete="off"> : 
				<input type="text" name="wp_lms_min" value="42" size="2" maxlength="2" autocomplete="off">
				<select name="wp_lms_ofday">
					<option value="am">AM</option>
					<option value="pm">PM</option>
				</select>
			</div>
			<div class="timestamp-wrap">
				<p>
					<strong>End Time</strong>
				</p>
				<label class="screen-reader-text">End Time</label>
				<input type="text" name="hh" value="10" size="2" maxlength="2" autocomplete="off"> : 
				<input type="text" name="mn" value="42" size="2" maxlength="2" autocomplete="off">
				<select name="mm">
					<option value="am">AM</option>
					<option value="pm">PM</option>
				</select>
			</div>
			<input type="hidden" id="ss" name="ss" value="<? date('s'); ?>">
			<input type="hidden" id="hidden_mm" name="hidden_mm" value="03">
			<input type="hidden" id="cur_mm" name="cur_mm" value="<? date('i'); ?>">
			<input type="hidden" id="hidden_jj" name="hidden_jj" value="13">
			<input type="hidden" id="cur_jj" name="cur_jj" value="<? date('d'); ?>">
			<input type="hidden" id="hidden_aa" name="hidden_aa" value="2014">
			<input type="hidden" id="cur_aa" name="cur_aa" value="<? date('Y'); ?>">
			<input type="hidden" id="hidden_hh" name="hidden_hh" value="10">
			<input type="hidden" id="cur_hh" name="cur_hh" value="<? date('h'); ?>">
			<input type="hidden" id="cur_mn" name="cur_mn" value="<? date('a'); ?>">
		</div>
		<p></p>
	</div>
	<div id="major-publishing-actions">
		<? if( count($POST) < 0 ) {?>
		<div id="delete-action">
			<a class="submitdelete deletion" href="http://grand/wp-admin/post.php?post=627&amp;action=trash&amp;_wpnonce=2a702372e1">Move to Queue</a>
		</div>
		<? } ?>
	<div id="publishing-action">
	<span class="spinner"></span>
		<input name="original_publish" type="hidden" id="original_publish" value="<? if( count($POST) < 0 ) {?>Update Course<? } else { ?>Schedule Course<? } ?>">
		<input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<? if( count($POST) < 0 ) {?>Update Course<? } else { ?>Schedule Course<? } ?>">
	</div>
	<div class="clear"></div>
	</div>
	</div>
		<?php
	}

	//array( $post_type => "", $tax => "", $orderby => "", $order => "" );
	public function form_open( $info, $page_base ){
		if( !isset($info['post_type']) ) return "<p>Post type has not been sent form_open function.</p>";
		$post_type = $info['post_type'];
		if( isset( $info['orderby'] ) ) $orderby = $info['orderby'];
		else $orderby = "title";
		if( isset( $info['order'] ) ) $order = $info['order'];
		else $order = "asc";
		if($order == 'asc') $switch_order = "desc";
		else $switch_order = 'asc';
		//$info = implode( ",", $info );
		query_posts( $info );
		$c = 0;
		?>
		<table class="wp-list-table widefat fixed pages" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" id="title" class="manage-column column-title sortable <? echo $switch_order; ?>" style=""><a href="<?php echo $page_base; ?>&amp;orderby=title&amp;order=<? echo $switch_order; ?>"><span>Title</span><span class="sorting-indicator"></span></a>
					</th>
					<th scope="col" id="instructor" class="manage-column column-instructor sortable <? echo $switch_order; ?>" style=""><a href="<?php echo $page_base; ?>&amp;orderby=instructor&amp;order=<? echo $switch_order; ?>"><span>Instructor</span><span class="sorting-indicator"></span></a>
					</th>
					<th scope="col" id="date-modified" class="manage-column column-date sortable <? echo $switch_order; ?>" style=""><a href="<?php echo $page_base; ?>&amp;orderby=date-modified&amp;order=<? echo $switch_order; ?>"><span>Date Modified</span><span class="sorting-indicator"></span></a>
					</th>
					<th scope="col" id="date" class="manage-column column-date sortable <? echo $switch_order; ?>" style=""><a href="<?php echo $page_base; ?>&amp;orderby=date&amp;order=<? echo $switch_order; ?>"><span>Date</span><span class="sorting-indicator"></span></a>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox">
					</th>
					<th scope="col" class="manage-column column-title sortable desc" style=""><a href="http://grand/wp-admin/edit.php?post_type=<?= $post_type; ?>&amp;orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a>
					</th>
					<th scope="col" class="manage-column column-instructor sortable desc" style=""><a href="http://grand/wp-admin/edit.php?post_type=<?= $post_type; ?>&amp;orderby=instructor&amp;order=asc"><span>Instructor</span><span class="sorting-indicator"></span></a>
					<th scope="col" class="manage-column column-date sortable <? echo $switch_order; ?>" style=""><a href="<?php echo $page_base; ?>&amp;orderby=date-modified&amp;order=<? echo $switch_order; ?>"><span>Date Modified</span><span class="sorting-indicator"></span></a>
					</th>
					<th scope="col" class="manage-column column-date sortable asc" style=""><a href="http://grand/wp-admin/edit.php?post_type=<?= $post_type; ?>&amp;orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>
				</tr>
			</tfoot>
			<tbody id="the-list">
			<?php while (have_posts()) : the_post(); ?>
				<?php 
				$c++;
				$this_post = get_post(get_the_ID());
				if($post_type == "course" && isset($_GET['page']) && $_GET['page'] == 'wp_lms_assign') {
		          	$term_array = $this->tax_names['assignment'];
		          	$prefix = "_a";
		          	$type = 'assignment';
		          	$course_obj = wp_get_post_terms($post->ID, $term_array[0]);
		          	$instructor_obj = wp_get_post_terms($post->ID, $term_array[1]);
		          	//for inactive course only, active courses will have this pre-defined
		          	//make method to check for the same course with more than one instructor
		          	//then another to return the instructor custom post type id's
		          	$page_query = new WP_Query();
					$all_pages = $page_query->query( array( 'post_type' => $type, $term_array[0] => $course_obj->ID, $term_array[1] => $instructor_obj->ID, 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
					//echo "<pre>";print_r($all_pages);echo "</pre>";
		          	//echo $type;
		          	$first_post = $all_pages[0]->ID;
		        }
		        else if($post_type == "course" && $_GET['page'] == 'wp_lms_assign') {
		          	$term_array = $this->tax_names['lecture'];
		          	$prefix = "_l";
		          	$type = 'lecture';
		          	//echo $type;
		        }
		        for($i=0;$i<2;$i++){
					$term =  $term_array[$i];
	          		$terms = wp_get_post_terms($first_post, $term);
	          		$var[] = $term;
	          		//echo " here ";
	          		if( is_array($terms) && !empty($terms) ) {
				        foreach ($terms as $termid) { 
				        	${$term} = $termid->term_id;
				           	// echo " ".${$term}." ";
				        }
			    	}
			        if( $var[0] == "course_name".$prefix && empty( $$var[0] ) ) $course_label = "Not Set";
			        if( $var[1] == "instructor_name".$prefix  && empty( $$var[1] ) ) $instructor_label = "Not Set";
			    }
				$add_assign_link = "";
				if( $post_type == 'course' || $post_type == 'lecture') { 
					$add_assign_link = '| </span><span class="view"><a class="viewpost" title="Add New Assignment" href="'.admin_url('post-new.php?post_type=assignment&amp;course='.get_the_ID()).'">Add New Assignment</a>';
					$link = "edit.php?s&post_status=all&post_type=".$type."&action=-1&m=0&course_name$prefix=".$$var[0]."&instructor_name$prefix=".$$var[1]."&paged=1&action2=-1";
					//$link = "#";
				}
				$level = "level-0";
				$front_sym = "";
				if( $this_post->post_parent != 0 ) {
					$level = "level-1";
					$front_sym = "&mdash; ";
				}
				?>
				<tr id="post-570" class="post-570 type-<?= $post_type; ?> status-publish hentry<? if($c%2 == 0) echo ' alternate'; ?> iedit author-self <?= $level; ?>" valign="top">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-570">Select <?php the_title(); ?></label>
						<input id="cb-select-570" type="checkbox" name="post[]" value="570">
						<div class="locked-indicator"></div>
					</th>
					<td class="post-title page-title column-title"><strong><a class="row-title" href="<?= $link; ?>" title="Edit “<?php the_title(); ?>”"><?= $front_sym; ?><?php the_title(); ?></a></strong>
						<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
						<div class="row-actions"><span class="edit"><a href="<?= get_edit_post_link( get_the_ID() ); ?>" title="Edit this item">Edit</a> <?= $add_assign_link; ?></span></div>
						<div class="hidden" id="inline_570">
							<div class="post_title">Eric Dowell</div>
							<div class="post_name">eric-dowell</div>
							<div class="post_author">1</div>
							<div class="comment_status">closed</div>
							<div class="ping_status">closed</div>
							<div class="_status">publish</div>
							<div class="jj">11</div>
							<div class="mm">03</div>
							<div class="aa">2014</div>
							<div class="hh">16</div>
							<div class="mn">13</div>
							<div class="ss">22</div>
							<div class="post_password"></div><div class="post_parent">0</div><div class="menu_order">0</div></div>
					</td>
					<td class="post-title page-title column-title"><strong><a class="row-title" href="<?= $link; ?>" title="Edit “<?php  ?>”"><?php wp_get_post_terms($post->ID, $term_array[1])->name; ?></a></strong>
					</td>
					<td class="date-modified column-date-modified"><abbr title="<? echo get_the_modified_date('Y-m-d')." ".get_the_modified_time(); ?>"><? echo get_the_modified_date()."<br>".get_the_modified_time(); ?></abbr><br>Modified</td>
					<td class="date column-date"><abbr title="<?php echo get_the_date('Y-m-d').' '.get_the_time(); ?>"><?php echo get_the_date(); echo "<br>"; the_time(); ?></abbr><br>Published</td>	
				</tr>
			<?php endwhile; ?>
			<? if( $c == 0 ) { 
				if( $post_type == "schedule") $empty_message = "No Course has been Scheduled";
				if( $post_type == "course") $empty_message = "No Course";
				?>

				<tr class="no-items"><td class="colspanchange" colspan="3"><?php echo $empty_message; ?></td></tr>
			<? } ?>
			</tbody>
		</table>
		<?
	}

} //end object
/**
 *  Creates Object
 *  @since 1.0.0
 **/
$wp_lms_html_gen = new wp_lms_html_gen();
?>