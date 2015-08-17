<?php
//// 20150817^RP  A shortcode which lists posts which match given criteria. 




//// 20150817^RP  Define the shortcode. 
//// Usage: `[ldc_list type="ldc_event" category="featured"]`
add_shortcode('ldc_list', 'ldc_list_shortcode');
function ldc_list_shortcode($atts = array()) {

  //// Parse and validate shortcode attributes. 
  //// @todo validate against all current post types
  //// @todo show error in a comment, if none are found
  $valid_type = array(
    'ldc_chatter' , // Chatter
    'ldc_creation', // Creation
    'ldc_event'   , // Event
  );
  $valid_orderby = array(
    'date'    , // order by date
    'none'    , // no order
    'ID'      , // order by post id. Note the capitalization
    'author'  , // order by author
    'title'   , // order by title
    'name'    , // order by post slug
    'modified', // order by last modified date
    'rand'    , // random order
  );
  $valid_order = array(
    'ASC' , // ascending order from lowest to highest values (1, 2, 3; a, b, c)
    'DESC', // descending order from highest to lowest values (3, 2, 1; c, b, a)
  );
  $atts = shortcode_atts( array(
    'type'     => $valid_type[0],
    'category' => '',
    'number'   => '-1', // show all by default
    'offset'   => '0',  // no offset
    'orderby'  => $valid_orderby[0],
    'order'    => $valid_order[0],
  ), $atts );
  if (! in_array($atts['type'], $valid_type) )       { $atts['type']    = $valid_type[0]; }
  if (! ctype_digit($atts['number']) )               { $atts['number']  = '-1'; }
  if (! ctype_digit($atts['offset']) )               { $atts['number']  = '0';  }
  if (! in_array($atts['orderby'], $valid_orderby) ) { $atts['orderby'] = $valid_orderby[0]; }
  if (! in_array($atts['order']  , $valid_order  ) ) { $atts['order']   = $valid_order[0]; }

  //// Begin the container <DIV>. 
  $out = array('<!-- BEGIN ' . rp_dev('List', 'ldc_list shortcode') . ' -->');
  $out[] = '<div class="ldc-list">';
  $out[] = '  <ul>';

  //// Retrieve each post. 
  $post_query = new WP_Query(
    array(
      'post_type'      => $atts['type'],
      'posts_per_page' => $atts['number'],
      'offset'         => $atts['offset'],
      'orderby'        => $atts['orderby'],
      'order'          => $atts['order'],
      'post_status'    => 'publish', // prevent default WP behavior, where logged in users see private posts
    )
  );

  // Add each post to the output-array.
  if (! $post_query->have_posts() ) {
    $out[] = array('<!-- ' . rp_dev('No posts found', 'Nothing matches type:' . $atts['type'] . ' category:' . $atts['category']) . ' -->');
  } else {
    while ( $post_query->have_posts() ) {
      $post_query->the_post();
      $post_id = get_the_id();
      // $post_images      = get_post_custom_values( 'pw_list_image'     , $post_id );
      // $post_subheadings = get_post_custom_values( 'pw_list_subheading', $post_id );
      $out[] = '    <li class="ldc-list-outer" id="ldc-list-id-' . $post_id . '">';
      // $out[] = '      <div class="look-portrait"><img class="face-me" src="' . $post_images[0] . '"></div>';
      // $out[] = '      <div class="look-triangle"><div></div></div>';
      $out[] = '      <div class="ldc-list-inner">';
      $out[] = '        <h4>' . get_the_title() . '</h4>';
      // $out[] = '        <h5>' . $post_subheadings[0]  . '</h5>';
      $out[] = '        <p>'  . get_the_content() . '</p>';
      $out[] = '      </div>';
      $out[] = '    </li>';
    };
  }
  
  //// Restore the $wp_query and global post data to the original main query. 
  wp_reset_query();

  //// End the container <DIV>. 
  $out[] = '  </ul>';
  $out[] = '</div>';
  $out[] = '<!-- END   ' . rp_dev('List', 'ldc_list shortcode') . ' -->';
  
  //// Convert the output-array to a string. 
  $out = "\n" . implode("\n", $out) . "\n";

  //// Prevent gaps appearing between <LI> elements, and return. 
  $out = str_replace("    </li>\n    <li", "    </li><li", $out);
  return $out;
}

?>