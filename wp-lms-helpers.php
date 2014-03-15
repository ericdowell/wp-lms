<?php

class wp_lms_helpers extends wp_lms {
	
	static public function sort_menu_order( $a, $b ) {
	    return strcmp( $a->menu_order, $b->menu_order );
	}

}