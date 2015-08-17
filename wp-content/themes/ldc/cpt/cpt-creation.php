<?php
//// 20150817^RP  An illustrated link to a creative project, eg Looptopia. 




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
add_filter('manage_ldc_creation_posts_columns', 'ldc_creation_table_head');
function ldc_creation_table_head($defaults) {
  $defaults['type'] = 'Type';
  return $defaults;
}

add_action('manage_ldc_creation_posts_custom_column', 'ldc_creation_table_content', 10, 2);
function ldc_creation_table_content($column_name, $post_id) {
  if ($column_name == 'type') {
    echo ldc_creation_link_to_type( get_post_meta($post_id, 'ldc_creation_link', true) );
  }
}




//// 20150817^RP  Make custom table headers sortable. 
//// https://wordpress.org/support/topic/admin-column-sorting
//// http://scribu.net/wordpress/custom-sortable-columns.html#comment-4732
//// @todo filter using custom fields
//// @todo sort by type, not by URL
add_filter('manage_edit-ldc_creation_sortable_columns', 'ldc_creation_table_sortable');
function ldc_creation_table_sortable($columns) {
  $columns['type'] = 'type';
  return $columns;
}

add_filter('request', 'ldc_creation_table_orderby');
function ldc_creation_table_orderby($vars) {
  if ( isset($vars['orderby']) && 'type' == $vars['orderby'] ) {
    $vars = array_merge($vars, array(
      'meta_key' => 'ldc_creation_link',
      'orderby'  => 'meta_value',
      //'orderby' => 'meta_value_num', // does not work
      //'order' => 'asc' // don't use this; blocks toggle UI
    ));
  }
  return $vars;
}




//// 20150817^RP  Define the 'Creation' custom post type and categories. 
//// https://codex.wordpress.org/Function_Reference/register_post_type
add_action('init', 'ldc_creation_cpt_define');
function ldc_creation_cpt_define() {

  register_post_type('ldc_creation',
    array(
      'public'        => true,
      'has_archive'   => true,
      'labels'        => array(
        'name'          => __('Creation'),
        'singular_name' => __('Creation'),
        'add_new_item'  => __('Add New Creation'),
        'edit_item'     => __('Edit Creation'),
        'new_item'      => __('New Creation'),
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

  register_taxonomy( 'ldc_creation_category', 'ldc_creation',
    array(
      'hierarchical'         => true, // `true` for categories, `false` for tags
      'show_ui'              => true,
      'show_admin_column'    => true,
      'query_var'            => true,
      'rewrite'              => array('slug' => 'templates'),
      'labels'               => array(
        'name'                 => __('Creation Categories'),
        'singular_name'        => __('Creation Category'),
        'search_items'         => __('Search Creation Categories'),
        'popular_items'        => __('Popular Creation Categories'),
        'all_items'            => __('All Creation Categories'),
        'edit_item'            => __('Edit Creation Category'),
        'update_item'          => __('Update Creation Category'),
        'add_new_item'         => __('Add New Creation Category'),
        'new_item_name'        => __('New Creation Category Name'),
        'add_or_remove_items'  => __('Add or Remove Creation Category'),
        'not_found'            => __('No Creation Categories found.'),
        'menu_name'            => __('Categories'),
      ),
    )
  );

}




//// 20150817^RP  Define meta boxes, and the fields they contain. 
add_action('cmb2_init', 'ldc_creation_meta_define');
function ldc_creation_meta_define() {

  //// Set a unique prefix for meta boxes and fields. 
  $prefix = 'ldc_creation_';


  //// COMMON

  //// The ldc_creation CPT only has one custom meta box. 
  $cmb_common = new_cmb2_box( array(
    'id'           => $prefix . 'custom',
    'title'        => __('Custom'),
    'object_types' => array('ldc_creation'),
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
//// a type like 'Loop.Coop'. 
//// Used by cpt/cpt-creation.php:ldc_creation_table_content()
function ldc_creation_link_to_type($link) {
  if ( strpos($link, 'loop.coop/') )  { return 'Loop.Coop'; }
  if ( strpos($link, 'meteor.com/') ) { return 'Meteor';    }
  return 'Error';
}




?>