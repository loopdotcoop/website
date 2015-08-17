<?php
//// 20150814^RP  An illustrated link to a Twitter Tweet, Pinterest Pin, etc. 




//// 20150814^RP  Flush CPT rewrite rules when the theme is changed. 
//// https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
add_action('after_switch_theme', 'ldc_flush_rewrite_rules');
if (! function_exists('ldc_flush_rewrite_rules') ) {
  function ldc_flush_rewrite_rules() {
    flush_rewrite_rules();
  }
}




//// 20150814^RP  Add custom table headers and columns to the admin listing. 
//// http://goo.gl/eUKz6L
add_filter('manage_ldc_chatter_posts_columns', 'ldc_chatter_table_head');
function ldc_chatter_table_head($defaults) {
  $defaults['type'] = 'Type';
  return $defaults;
}

add_action('manage_ldc_chatter_posts_custom_column', 'ldc_chatter_table_content', 10, 2);
function ldc_chatter_table_content($column_name, $post_id) {
  if ($column_name == 'type') {
    echo ldc_chatter_link_to_type( get_post_meta($post_id, 'ldc_chatter_link', true) );
  }
}




//// 20150814^RP  Make custom table headers sortable. 
//// https://wordpress.org/support/topic/admin-column-sorting
//// http://scribu.net/wordpress/custom-sortable-columns.html#comment-4732
//// @todo filter using custom fields
//// @todo sort by type, not by URL
add_filter('manage_edit-ldc_chatter_sortable_columns', 'ldc_chatter_table_sortable');
function ldc_chatter_table_sortable($columns) {
  $columns['type'] = 'type';
  return $columns;
}

add_filter('request', 'ldc_chatter_table_orderby');
function ldc_chatter_table_orderby($vars) {
  if ( isset($vars['orderby']) && 'type' == $vars['orderby'] ) {
    $vars = array_merge($vars, array(
      'meta_key' => 'ldc_chatter_link',
      'orderby'  => 'meta_value',
      //'orderby' => 'meta_value_num', // does not work
      //'order' => 'asc' // don't use this; blocks toggle UI
    ));
  }
  return $vars;
}




//// 20150814^RP  Define the 'Chatter' custom post type and categories. 
//// https://codex.wordpress.org/Function_Reference/register_post_type
add_action('init', 'ldc_chatter_cpt_define');
function ldc_chatter_cpt_define() {

  register_post_type('ldc_chatter',
    array(
      'public'        => true,
      'has_archive'   => true,
      'labels'        => array(
        'name'          => __('Chatter'),
        'singular_name' => __('Chatter'),
        'add_new_item'  => __('Add New Chatter'),
        'edit_item'     => __('Edit Chatter'),
        'new_item'      => __('New Chatter'),
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

  register_taxonomy( 'ldc_chatter_category', 'ldc_chatter',
    array(
      'hierarchical'         => true, // `true` for categories, `false` for tags
      'show_ui'              => true,
      'show_admin_column'    => true,
      'query_var'            => true,
      'rewrite'              => array('slug' => 'templates'),
      'labels'               => array(
        'name'                 => __('Chatter Categories'),
        'singular_name'        => __('Chatter Category'),
        'search_items'         => __('Search Chatter Categories'),
        'popular_items'        => __('Popular Chatter Categories'),
        'all_items'            => __('All Chatter Categories'),
        'edit_item'            => __('Edit Chatter Category'),
        'update_item'          => __('Update Chatter Category'),
        'add_new_item'         => __('Add New Chatter Category'),
        'new_item_name'        => __('New Chatter Category Name'),
        'add_or_remove_items'  => __('Add or Remove Chatter Category'),
        'not_found'            => __('No Chatter Categories found.'),
        'menu_name'            => __('Categories'),
      ),
    )
  );

}




//// 20150814^RP  Define meta boxes, and the fields they contain. 
add_action('cmb2_init', 'ldc_chatter_meta_define');
function ldc_chatter_meta_define() {

  //// Set a unique prefix for meta boxes and fields. 
  $prefix = 'ldc_chatter_';


  //// COMMON

  //// The ldc_chatter CPT only has one custom meta box. 
  $cmb_common = new_cmb2_box( array(
    'id'           => $prefix . 'custom',
    'title'        => __('Custom'),
    'object_types' => array('ldc_chatter'),
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




//// 20150814^RP  Convert a link URL like https://twitter.com/foo/status/123 to 
//// a type like 'Twitter'. 
//// Used by cpt/cpt-chatter.php:ldc_chatter_table_content()
function ldc_chatter_link_to_type($link) {
  if ( strpos($link, '://twitter.com/') )      { return 'Twitter';  }
  if ( strpos($link, '://facebook.com/') )     { return 'Facebook'; }
  if ( strpos($link, '://www.facebook.com/') ) { return 'Facebook'; }
  return 'Error';
}




?>