<?

class wp_lms_post_types extends wp_lms {
	
    /**
     *  All of the Custom Post Types and Taxonomy are registered
     *  @since 1.0.0
     */
    public function post_types() {

			register_post_type('session', 
				array(
				'label' => 'Sessions',
				'description' => 'This is used to created session to be used with scheduling active classes.',
				'public' => true,
				'show_ui' => false,
				'show_in_menu' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => true,
				'rewrite' => array('slug' => 'session', 'with_front' => true),
				'query_var' => true,
				'menu_position' => '5',
				'menu_icon' => $this->plugin_img_url.'png/24/schedule.png',
				'supports' => array('title','custom-fields','revisions','thumbnail','page-attributes'),
				'labels' => array (
				  'name' => 'Sessions',
				  'singular_name' => 'Session',
				  'menu_name' => 'Sessions',
				  'add_new' => 'Add Session',
				  'add_new_item' => 'Add New Session',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Session',
				  'new_item' => 'New Session',
				  'view' => 'View Session',
				  'view_item' => 'View Session',
				  'search_items' => 'Search Sessions',
				  'not_found' => 'No Sessions Found',
				  'not_found_in_trash' => 'No Sessions Found in Trash',
				  'parent' => 'Parent Session',
					)
				) 
			);
			register_post_type('instructor', 
				array(
				'label' => 'Instructors',
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => 'instructor', 'with_front' => true),
				'query_var' => true,
				'has_archive' => true,
				'menu_position' => '5',
				'menu_icon' => $this->plugin_img_url.'png/24/person.png',
				'supports' => array('title','excerpt','custom-fields','revisions','thumbnail'),
				'labels' => array (
				  'name' => 'Instructors',
				  'singular_name' => 'Instructor',
				  'menu_name' => 'Instructors',
				  'add_new' => 'Add Instructor',
				  'add_new_item' => 'Add New Instructor',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Instructor',
				  'new_item' => 'New Instructor',
				  'view' => 'View Instructor',
				  'view_item' => 'View Instructor',
				  'search_items' => 'Search Instructors',
				  'not_found' => 'No Instructors Found',
				  'not_found_in_trash' => 'No Instructors Found in Trash',
				  'parent' => 'Parent Instructor',
					)
				) 
			);
			register_post_type('course', 
				array(
				'label' => 'Courses',
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => 'course', 'with_front' => true),
				'query_var' => true,
				'has_archive' => true,
				'menu_position' => '5',
				'menu_icon' => $this->plugin_img_url.'png/24/filing.png',
				'supports' => array('title','editor','custom-fields','revisions','thumbnail'),
				'labels' => array (
				  'name' => 'Courses',
				  'singular_name' => 'Course',
				  'menu_name' => 'Courses',
				  'add_new' => 'Add Course',
				  'add_new_item' => 'Add New Course',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Course',
				  'new_item' => 'New Course',
				  'view' => 'View Course',
				  'view_item' => 'View Course',
				  'search_items' => 'Search Courses',
				  'not_found' => 'No Courses Found',
				  'not_found_in_trash' => 'No Courses Found in Trash',
				  'parent' => 'Parent Course',
					)
				) 
			);
    	register_post_type('student_directory', 
    		array(
				'label' => 'Student Directory',
				'description' => '',
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => true,
				'rewrite' => array('slug' => 'student_directory', 'with_front' => true),
				'query_var' => true,
				'exclude_from_search' => true,
				'menu_position' => '5',
				'menu_icon' => $this->plugin_img_url.'png/24/directory.png',
				'supports' => array('title','custom-fields','revisions','thumbnail','page-attributes'),
				'labels' => array (
				  'name' => 'Student Directory',
				  'singular_name' => 'Student',
				  'menu_name' => 'Student Directory',
				  'add_new' => 'Add Student',
				  'add_new_item' => 'Add New Student',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Student',
				  'new_item' => 'New Student',
				  'view' => 'View Student',
				  'view_item' => 'View Student',
				  'search_items' => 'Search Student Directory',
				  'not_found' => 'No Student Directory Found',
				  'not_found_in_trash' => 'No Student Directory Found in Trash',
				  'parent' => 'Parent Student',
					)
				) 
			);
			register_post_type('assignment', 
				array(
				'label' => 'Assignments',
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => true,
				'rewrite' => array('slug' => 'assignment', 'with_front' => true),
				'query_var' => true,
				'has_archive' => true,
				'menu_position' => '5',
				'menu_icon' => $this->plugin_img_url.'png/24/assignment.png',
				'supports' => array('title','editor','custom-fields','revisions','thumbnail','page-attributes'),
				'labels' => array (
				  'name' => 'Assignments',
				  'singular_name' => 'Assignment',
				  'menu_name' => 'Assignments',
				  'add_new' => 'Add Assignment',
				  'add_new_item' => 'Add New Assignment',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Assignment',
				  'new_item' => 'New Assignment',
				  'view' => 'View Assignment',
				  'view_item' => 'View Assignment',
				  'search_items' => 'Search Assignments',
				  'not_found' => 'No Assignments Found',
				  'not_found_in_trash' => 'No Assignments Found in Trash',
				  'parent' => 'Parent Assignment',
					)
				) 
			);
			register_post_type('lecture', 
				array(
				'label' => 'Lectures',
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => true,
				'rewrite' => array('slug' => 'lecture', 'with_front' => true),
				'query_var' => true,
				'has_archive' => true,
				'menu_position' => '5',
				'menu_icon' => $this->plugin_img_url.'png/24/lecture.png',
				'supports' => array('title','editor','custom-fields','revisions','thumbnail','page-attributes'),
				'labels' => array (
				  'name' => 'Lectures',
				  'singular_name' => 'Lecture',
				  'menu_name' => 'Lectures',
				  'add_new' => 'Add Lecture',
				  'add_new_item' => 'Add New Lecture',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Lecture',
				  'new_item' => 'New Lecture',
				  'view' => 'View Lecture',
				  'view_item' => 'View Lecture',
				  'search_items' => 'Search Lectures',
				  'not_found' => 'No Lectures Found',
				  'not_found_in_trash' => 'No Lectures Found in Trash',
				  'parent' => 'Parent Lecture',
					)
				) 
			);
			register_post_type('timeline', 
				array(
				'label' => 'Timeline',
				'description' => '',
				'public' => true,
				'show_ui' => false,
				'show_in_menu' => false,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => true,
				'rewrite' => array('slug' => 'timeline', 'with_front' => true),
				'query_var' => true,
				'menu_position' => '5',
				'supports' => array('title','editor','custom-fields','revisions','thumbnail','page-attributes'),
				'labels' => array (
				  'name' => 'Timeline',
				  'singular_name' => 'Timeline',
				  'menu_name' => 'Timeline',
				  'add_new' => 'Add Timeline',
				  'add_new_item' => 'Add New Timeline',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Timeline',
				  'new_item' => 'New Timeline',
				  'view' => 'View Timeline',
				  'view_item' => 'View Timeline',
				  'search_items' => 'Search Timeline',
				  'not_found' => 'No Timeline Found',
				  'not_found_in_trash' => 'No Timeline Found in Trash',
				  'parent' => 'Parent Timeline',
					)
				) 
			);
			register_post_type('schedule', 
				array(
				'label' => 'Schedules',
				'description' => '',
				'public' => true,
				'show_ui' => false,
				'show_in_menu' => false,
				'capability_type' => 'page',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => 'schedule', 'with_front' => true),
				'query_var' => true,
				'menu_position' => '5',
				'supports' => array('title','custom-fields','revisions','page-attributes'),
				'labels' => array (
				  'name' => 'Schedules',
				  'singular_name' => 'Schedule',
				  'menu_name' => 'Schedules',
				  'add_new' => 'Add Schedule',
				  'add_new_item' => 'Add New Schedule',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Schedule',
				  'new_item' => 'New Schedule',
				  'view' => 'View Schedule',
				  'view_item' => 'View Schedule',
				  'search_items' => 'Search Schedules',
				  'not_found' => 'No Schedules Found',
				  'not_found_in_trash' => 'No Schedules Found in Trash',
				  'parent' => 'Parent Schedule',
					)
				) 
			);
			/**
			 *	 Taxonomy
			*/
			register_taxonomy( 'course_start_date',
				array(
			  	0 => 'course',
				),
				array( 'hierarchical' => false,
					'label' => 'Start Date',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => false,
					'labels' => array(
					  'search_items' => 'Start Date',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			); 
			register_taxonomy( 'course_status',
				array(
			  	0 => 'course',
				),
				array( 'hierarchical' => false,
					'label' => 'Course Status',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => true,
						'labels' => array(
					  'search_items' => 'Course Status',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( 'course_instructor_name',
				array(
			  	0 => 'course',
				),
				array( 'hierarchical' => false,
					'label' => 'Instructor',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => true,
						'labels' => array(
					  'search_items' => 'Instructor',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( 'course_days_name',
				array(
				  0 => 'course',
				),
				array( 'hierarchical' => false,
					'label' => 'Course Days',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => true,
					'labels' => array(
					  'search_items' => 'Course Day',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( 'course_days_num',
				array(
				  0 => 'course',
				),
				array( 'hierarchical' => false,
					'label' => 'Course Day Numbers',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => false,
					'labels' => array(
					  'search_items' => 'Course Day Number',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( '_instructor_name_lecture', 
				array(
			  	0 => 'lecture',
				),
				array( 'hierarchical' => false,
					'label' => 'Instructor',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => true,
					'labels' => array(
					  'search_items' => 'Instructor',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( '_instructor_num_lecture',
				array(
			  	0 => 'lecture',
				),
				array( 'hierarchical' => false,
					'label' => 'Instructor',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => false,
					'labels' => array(
					  'search_items' => 'Instructor',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( '_course_name_lecture',
				array(
			  	0 => 'lecture',
				),
				array( 'hierarchical' => false,
					'label' => 'Course',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => true,
					'labels' => array(
					  'search_items' => 'Course',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( '_course_num_lecture',
				array(
			  	0 => 'lecture',
				),
				array( 'hierarchical' => false,
					'label' => 'Course',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => false,
					'labels' => array(
					  'search_items' => 'Course',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( '_instructor_name_assignment',
				array(
			  	0 => 'assignment',
				),
				array( 'hierarchical' => false,
					'label' => 'Instructor',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => true,
					'labels' => array(
					  'search_items' => 'Instructor',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( '_instructor_num_assignment',
				array(
			  	0 => 'assignment',
				),
				array( 'hierarchical' => false,
					'label' => 'Instructor',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => false,
					'labels' => array(
					  'search_items' => 'Instructor',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( '_course_name_assignment',
				array(
			  	0 => 'assignment',
				),
				array( 'hierarchical' => false,
					'label' => 'Course',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => true,
					'labels' => array(
					  'search_items' => 'Course',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				) 
			);
			register_taxonomy( '_course_num_assignment',
				array(
				  0 => 'assignment',
				),
				array( 'hierarchical' => false,
					'label' => 'Course',
					'show_ui' => false,
					'query_var' => true,
					'show_admin_column' => false,
					'labels' => array(
					  'search_items' => 'Course',
					  'popular_items' => '',
					  'all_items' => '',
					  'parent_item' => '',
					  'parent_item_colon' => '',
					  'edit_item' => '',
					  'update_item' => '',
					  'add_new_item' => '',
					  'new_item_name' => '',
					  'separate_items_with_commas' => '',
					  'add_or_remove_items' => '',
					  'choose_from_most_used' => '',
					)
				)
			); 

    } //post_types 

}//end object

$wp_lms_post_types = new wp_lms_post_types();

?>