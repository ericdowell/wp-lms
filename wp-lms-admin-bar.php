<?php


class wp_lms_adminbar extends wp_lms {
	

	public function content_menu() {
      global $wp_admin_bar;
      $wp_admin_bar->add_menu( array(
            'parent' => 'new-content',
            'id' => 'new_assignment',
            'title' => 'Assignment',
            'href' => admin_url( 'post-new.php?post_type=assignment' ),
            )
      );
      $wp_admin_bar->add_menu( array(
            'parent' => 'new-content',
            'id' => 'new_lecture',
            'title' => 'Lecture',
            'href' => admin_url( 'post-new.php?post_type=lecture' ),
            )
      );
      $args = array(
        'id'    => 'wp_lms',
        'title' => 'WP LMS'
      );
      $wp_admin_bar->add_node( $args );

      // add a child item to our parent item
      $args = array(
        'id'     => 'go_to_assignments',
        'title'  => 'Assignments',
        'parent' => 'wp_lms',
        'href' => admin_url( 'edit.php?post_type=assignment' ),
      );
      $wp_admin_bar->add_node( $args );

      $args = array(
        'id'     => 'go_to_lectures',
        'title'  => 'Lectures',
        'parent' => 'wp_lms',
        'href' => admin_url( 'edit.php?post_type=lecture' ),
      );
      $wp_admin_bar->add_node( $args );

      $args = array(
        'id'     => 'go_to_students',
        'title'  => 'Students',
        'parent' => 'wp_lms',
        'href' => admin_url( 'edit.php?post_type=student_directory' ),
      );
      $wp_admin_bar->add_node( $args );
    }

}

$wp_lms_admin_bar = new wp_lms_adminbar();

?>