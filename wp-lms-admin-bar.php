<?php


class wp_lms_adminbar extends wp_lms {
  

  public function content_menu() {
      global $wp_admin_bar;
      $args = array(
        'id'    => 'wp_lms',
        'title' => 'Go To',
        'meta'  => array( 'class' => 'wp-lms-toolbar' )
      );
      $wp_admin_bar->add_node( $args );

      // add a child item to our parent item
      $args = array(
        'id'     => 'go_to_posts',
        'title'  => 'Posts',
        'parent' => 'wp_lms',
        'href' => admin_url( 'edit.php' ),
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

      $args = array(
        'id'     => 'go_to_instructors',
        'title'  => 'Instructors',
        'parent' => 'wp_lms',
        'href' => admin_url( 'edit.php?post_type=instructor' ),
      );
      $wp_admin_bar->add_node( $args );

      $args = array(
        'id'     => 'go_to_courese',
        'title'  => 'Courses',
        'parent' => 'wp_lms',
        'href' => admin_url( 'edit.php?post_type=course' ),
      );
      $wp_admin_bar->add_node( $args );

      $args = array(
        'id'    => 'wp_lms_attendace',
        'title' => 'Attendance',
        'meta'  => array( 'class' => 'wp-lms-toolbar' ),
      );
      $wp_admin_bar->add_node( $args );

      $args = array(
        'id'     => 'wp_lms_attendace_take',
        'title'  => 'Take',
        'parent' => 'wp_lms_attendace',
        'href' => admin_url( 'edit.php?post_type=student_directory&page=wp_lms_student_attendance' ),
      );
      $wp_admin_bar->add_node( $args );
    }

}

$wp_lms_admin_bar = new wp_lms_adminbar();

?>