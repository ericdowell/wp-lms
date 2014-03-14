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
		$all_pages = $page_query->query( array( 'post_type' => $type, 'posts_per_page' => -1, 'orderby' => 'title',
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
		<div id="timestampdiv" class="hide-if-js" style="display: block;">
			<p>
				<strong>Begin Date</strong>
			</p>
			<label class="screen-reader-text">Begin Date</label>
			<div class="timestamp-wrap">
				<select id="mm" name="mm">
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
				
				<input type="text" id="jj" name="jj" value="13" size="2" maxlength="2" autocomplete="off">, 
				<input type="text" id="aa" name="aa" value="2014" size="4" maxlength="4" autocomplete="off"><?
				if($time != 'no-time') {
				?> @ 
				<input type="text" id="hh" name="hh" value="10" size="2" maxlength="2" autocomplete="off"> : 
				<input type="text" id="mn" name="mn" value="42" size="2" maxlength="2" autocomplete="off">
				<?
				}
				?>
			</div>
			<div class="timestamp-wrap">
				<p>
					<strong>End Date</strong>
				</p>
				<label class="screen-reader-text">End Date</label>
				<select id="mm" name="mm">
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
				
				<input type="text" id="jj" name="jj" value="13" size="2" maxlength="2" autocomplete="off">, 
				<input type="text" id="aa" name="aa" value="2014" size="4" maxlength="4" autocomplete="off"><?
				if($time != 'no-time') {
				?> @ 
				<input type="text" id="hh" name="hh" value="10" size="2" maxlength="2" autocomplete="off"> : 
				<input type="text" id="mn" name="mn" value="42" size="2" maxlength="2" autocomplete="off">
				<?
				}
				?>
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
				$add_assign_link = "";
				if( $post_type == 'course') { 
					$add_assign_link = '| </span><span class="view"><a class="viewpost" title="Add New Assignment" href="'.admin_url('post-new.php?post_type=assignment&amp;course='.get_the_ID()).'">Add New Assignment</a>';
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
					<td class="post-title page-title column-title"><strong><a class="row-title" href="#" title="Edit “<?php the_title(); ?>”"><?= $front_sym; ?><?php the_title(); ?></a></strong>
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
					<td class="date-modified column-date-modified"><abbr title="<? echo get_the_modified_date('Y-m-d')." ".get_the_modified_time(); ?>"><? echo get_the_modified_date()."<br>".get_the_modified_time(); ?></abbr><br>Modified</td>
					<td class="date column-date"><abbr title="<?php echo get_the_date('Y-m-d').' '.get_the_time(); ?>"><?php echo get_the_date(); echo "<br>"; the_time(); ?></abbr><br>Published</td>	
				</tr>
			<?php endwhile; ?>
			</tbody>
			</table>
		</div>
		<?
	}

} //end object
/**
 *  Creates Object
 *  @since 1.0.0
 **/
$wp_lms_html_gen = new wp_lms_html_gen();
?>