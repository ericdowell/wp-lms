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
			default:
				return "<p>Seem there isn't a output for <strong>".$type."</strong> defined.</p>";
				break;
		}
	} //end function
	//array( $post_type => "", $tax => "", $orderby => "", $order => "" );
	public function form_open( $info, $page_base ){
		foreach($info as $key => $val) {
			if( !empty( $val ) ) {
				$args[$key] = $val;
			}
		}
		if( isset( $args['orderby'] ) ) $orderby = $args['orderby'];
		else $orderby = "title";
		if( isset( $args['order'] ) ) $order = $args['order'];
		else $order = "asc";
		if($order == 'asc') $switch_order = "desc";
		else $switch_order = 'asc';
		//$args = implode( ",", $args );
		query_posts( $args );
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
						<th scope="col" class="manage-column column-title sortable desc" style=""><a href="http://grand/wp-admin/edit.php?post_type=<?= $args["post_type"]; ?>&amp;orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" class="manage-column column-date sortable <? echo $switch_order; ?>" style=""><a href="<?php echo $page_base; ?>&amp;orderby=date-modified&amp;order=<? echo $switch_order; ?>"><span>Date Modified</span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" class="manage-column column-date sortable asc" style=""><a href="http://grand/wp-admin/edit.php?post_type=<?= $args["post_type"]; ?>&amp;orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>
					</tr>
				</tfoot>
				<tbody id="the-list">
		<?php while (have_posts()) : the_post(); ?>
				<?php 
				$c++;
				$this_post = get_post(get_the_ID());
				$add_assign_link = "";
				if( $args['post_type'] == 'course') { 
					$add_assign_link = '| </span><span class="view"><a class="viewpost" title="Add New Assignment" href="'.admin_url('post-new.php?post_type=assignment&amp;course='.get_the_ID()).'">Add New Assignment</a>';
				}
				if( $this_post->post_parent == 0 ) {

				}
				?>
				<tr id="post-570" class="post-570 type-<?= $args['post_type']; ?> status-publish hentry<? if($c%2 == 0) echo ' alternate'; ?> iedit author-self level-0" valign="top">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-570">Select <?php the_title(); ?></label>
						<input id="cb-select-570" type="checkbox" name="post[]" value="570">
						<div class="locked-indicator"></div>
					</th>
					<td class="post-title page-title column-title"><strong><a class="row-title" href="#" title="Edit “<?php the_title(); ?>”"><?php the_title(); ?></a></strong>
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