<?php

class wp_lms_helpers extends wp_lms {
	
	static public function sort_menu_order( $a, $b ) {
	    return strcmp( $a->menu_order, $b->menu_order );
	}
	// public function wp_lms_query( $query, $info ) {
	// 	switch ($query) {
	// 		case 'WP_Query':
	// 			$page_query = new WP_Query();
	// 	      	return $page_query->query( $args );
	// 			break;
	// 		case "get_pages":
	// 			return get_pages( $args );
	// 			break;
	// 		case "query_posts":
	// 			return "Call it yourself";
	// 			break;
	// 		default:
	// 			# code...
	// 			break;
	// 	}
	// }

}