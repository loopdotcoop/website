<?php
//// 20150817^RP  An illustrated link to an event on Facebook, Eventbrite, etc. 




//// 20150814^RP  Flush CPT rewrite rules when the theme is changed. 
//// https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
add_action('after_switch_theme', 'ldc_flush_rewrite_rules');
if (! function_exists('ldc_flush_rewrite_rules') ) {
  function ldc_flush_rewrite_rules() {
    flush_rewrite_rules();
  }
}




//// 20150817^RP  Add custom table headers and columns to the admin listing. 
//// http://goo.gl/eUKz6L
add_filter('manage_ldc_event_posts_columns', 'ldc_event_table_head');
function ldc_event_table_head($defaults) {
  $defaults['type'] = 'Type';
  return $defaults;
}

add_action('manage_ldc_event_posts_custom_column', 'ldc_event_table_content', 10, 2);
function ldc_event_table_content($column_name, $post_id) {
  if ($column_name == 'type') {
    echo ldc_link_to_type( get_post_meta($post_id, 'ldc_event_link', true) );
  }
}




//// 20150817^RP  Make custom table headers sortable. 
//// https://wordpress.org/support/topic/admin-column-sorting
//// http://scribu.net/wordpress/custom-sortable-columns.html#comment-4732
//// @todo filter using custom fields
//// @todo sort by type, not by URL
add_filter('manage_edit-ldc_event_sortable_columns', 'ldc_event_table_sortable');
function ldc_event_table_sortable($columns) {
  $columns['type'] = 'type';
  return $columns;
}

add_filter('request', 'ldc_event_table_orderby');
function ldc_event_table_orderby($vars) {
  if ( isset($vars['orderby']) && 'type' == $vars['orderby'] ) {
    $vars = array_merge($vars, array(
      'meta_key' => 'ldc_event_link',
      'orderby'  => 'meta_value',
      //'orderby' => 'meta_value_num', // does not work
      //'order' => 'asc' // don't use this; blocks toggle UI
    ));
  }
  return $vars;
}




//// 20150817^RP  Define the 'Event' custom post type and categories. 
//// https://codex.wordpress.org/Function_Reference/register_post_type
add_action('init', 'ldc_event_cpt_define');
function ldc_event_cpt_define() {

  register_post_type('ldc_event',
    array(
      'public'        => true,
      'has_archive'   => true,
      'labels'        => array(
        'name'          => __('Events'),
        'singular_name' => __('Event'),
        'add_new_item'  => __('Add New Event'),
        'edit_item'     => __('Edit Event'),
        'new_item'      => __('New Event'),
      ),
      'supports'  => array(
        'title',
        'editor',
        'author',
        'thumbnail', // Featured Image
        'excerpt',
        //'revisions',
        //'trackbacks',
        //'custom-fields',
        //'comments',
        //'sticky',
      ),
    )
  );

  register_taxonomy( 'ldc_event_category', 'ldc_event',
    array(
      'hierarchical'         => true, // `true` for categories, `false` for tags
      'show_ui'              => true,
      'show_admin_column'    => true,
      'query_var'            => true,
      'rewrite'              => array('slug' => 'templates'),
      'labels'               => array(
        'name'                 => __('Event Categories'),
        'singular_name'        => __('Event Category'),
        'search_items'         => __('Search Event Categories'),
        'popular_items'        => __('Popular Event Categories'),
        'all_items'            => __('All Event Categories'),
        'edit_item'            => __('Edit Event Category'),
        'update_item'          => __('Update Event Category'),
        'add_new_item'         => __('Add New Event Category'),
        'new_item_name'        => __('New Event Category Name'),
        'add_or_remove_items'  => __('Add or Remove Event Category'),
        'not_found'            => __('No Event Categories found.'),
        'menu_name'            => __('Categories'),
      ),
    )
  );

}




//// 20150817^RP  Define meta boxes, and the fields they contain. 
add_action('cmb2_init', 'ldc_event_meta_define');
function ldc_event_meta_define() {

  //// Set a unique prefix for meta boxes and fields. 
  $prefix = 'ldc_event_';


  //// COMMON

  //// The ldc_event CPT only has one custom meta box. 
  $cmb_common = new_cmb2_box( array(
    'id'           => $prefix . 'custom',
    'title'        => __('Custom'),
    'object_types' => array('ldc_event'),
    'context'      => 'normal',
    'priority'     => 'high',
    'show_names'   => true, // Show field names on the left
  ));

  //// Xx. 
  $cmb_common->add_field( array(
    'name'             => 'Link',
    'id'               => $prefix . 'link',
    'description'      => __('The full URL of the original content'),
    'type'             => 'text_url',
  ));


}




?>