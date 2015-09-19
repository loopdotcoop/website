<?php
//// 20150817^RP  A shortcode which lists posts which match given criteria. 




//// 20150817^RP  Define the shortcode. 
//// Usage: `[ldc_list type="ldc_event" category="featured"]`
add_shortcode('ldc_list', 'ldc_list_shortcode');
function ldc_list_shortcode($atts = array()) {

  //// Load the clientside code and the stylesheet, if not already loaded. 
  //// See /sc/sc-ldc_list.php:ldc_list_register_deps()
  //// http://jameskoster.co.uk/work/using-wordpress-3-8s-dashicons-theme-plugin/
  wp_enqueue_script('ldc_list_script');
  wp_enqueue_style( 'ldc_list_style');
  wp_enqueue_style('dashicons');

  //// Define valid shortcode attributes. 
  $valid_type = array( // @todo add all current post types
    'ldc_chatter' , // Chatter
    'ldc_creation', // Creation
    'ldc_event'   , // Event
  );
  $valid_status = array(
    'publish' ,   // (default) a published post or page
    'pending',    // pending review
    'draft',      // in draft status
    'auto-draft', // a newly created post, with no content
    'future',     // a post to publish in the future
    'private',    // not visible to users who are not logged in
    'inherit',    // a revision. see https://codex.wordpress.org/Function_Reference/get_children
    'trash',      // post is in trashbin
    'any',        // any status except those from post statuses with 'exclude_from_search' set to true (i.e. trash and auto-draft)
  );
  $valid_orderby = array(
    'date'    , // (default) order by date
    'none'    , // no order
    'ID'      , // order by post id. Note the capitalization
    'author'  , // order by author
    'title'   , // order by title
    'name'    , // order by post slug
    'modified', // order by last modified date
    'rand'    , // random order
  );
  $valid_order = array(
    'ASC' , // (default) ascending order from lowest to highest values (1, 2, 3; a, b, c)
    'DESC', // descending order from highest to lowest values (3, 2, 1; c, b, a)
  );

  //// Set defaults for missing attributes. 
  $atts = shortcode_atts( array(
    'type'     => $valid_type[0],
    'status'   => $valid_status[0],
    'orderby'  => $valid_orderby[0],
    'order'    => $valid_order[0],
    'number'   => '-1', // show all by default
    'offset'   => '0',  // no offset
    'category' => '',
    'class'    => false,
    'linkkey'  => '',
  ), $atts );

  //// Prepare an array which will contain warnings (that is, not-quite-errors). 
  $warnings = array();

  //// Validate attributes. 
  if (! ldc_list_validate($atts['type'],    $valid_type    ) )    { ldc_list_defaultize($warnings, $atts, 'type'    , $valid_type[0]);    }
  if (! ldc_list_validate($atts['status'],  $valid_status  ) )    { ldc_list_defaultize($warnings, $atts, 'status'  , $valid_status[0]);  }
  if (!          in_array($atts['orderby'], $valid_orderby) )     { ldc_list_defaultize($warnings, $atts, 'orderby' , $valid_orderby[0]); }
  if (!          in_array($atts['order']  , $valid_order  ) )     { ldc_list_defaultize($warnings, $atts, 'order'   , $valid_order[0]);   }
  if ('-1' !== $atts['number'] && ! ctype_digit($atts['number'])) { ldc_list_defaultize($warnings, $atts, 'number'  , '-1');              }
  if (! ctype_digit($atts['offset']) )                            { ldc_list_defaultize($warnings, $atts, 'offset'  , '0');               }

  //// Derive the link key from the type, if not explicitly set. 
  if ('' == $atts['linkkey']) { $atts['linkkey'] = $atts['type'] . '_link'; }

  //// Begin the container <DIV>. 
  $out = array('<!-- BEGIN ' . rp_dev('List', 'ldc_list shortcode') . ' -->');
  $out[] = '<div class="ldc-list-wrap ldc-preload' . ($atts['class'] ? ' ' . $atts['class'] : '') . '">';
  $out[] = '  <ul>';

  //// Configure the query. 
  $query_args = array(
    'post_type'      => $atts['type'],
    'post_status'    => $atts['status'],
    'orderby'        => $atts['orderby'],
    'order'          => $atts['order'],
    'posts_per_page' => $atts['number'],
    'offset'         => $atts['offset'],
  );
  if ($atts['category']) {
    $query_args['tax_query'] = array(array(
      'taxonomy'       => $atts['type'] . '_category',
      'field'          => 'slug',
      'terms'          => $atts['category'],
    ));
  }

  //// Retrieve matching posts. 
  $posts = new WP_Query($query_args);

  //// Show any warnings. 
  if (count($warnings)) {
    $out[] =
        '    <!-- '
      . rp_dev(
          count($warnings) . ' Shortcode Attribute Warning' . (1 == count($warnings) ? '' : 's'),
          implode(" ... ", $warnings))
      . ' -->'
    ;
  }

  //// Get the URL path to the media directory. 
  $upload_dir  = wp_upload_dir();
  $upload_base = $upload_dir['baseurl'];

  //// Add each post to the output-array... 
  if ($posts->have_posts()) {

    while ($posts->have_posts()) {

      //// Switch WP's focus to the current post, and get various data from it. 
      $posts->the_post();
      $post_id   = get_the_id();
      $post_link = get_post_meta($post_id, $atts['linkkey'], true);
      $link_type = ldc_link_to_type($post_link);
      $image_id  = get_post_thumbnail_id($post_id);

      //// Begin the opening <LI> tag. 
      $out[] = '    <li id="ldc-list-id-' . $post_id . '" class="ldc-list-post '
        . 'ldc-link-type-' . str_replace('.', '-', strtolower($link_type) )
        . '"';

      //// If the post has a featured-image, show that as the background, and 
      //// add a data-attribute so that JavaScript can assign the proper height. 
      if ($image_id) {
        $image_meta = wp_get_attachment_metadata($image_id, false);
        $out[count($out)-1] .=
            ' style="background-image:url(\'' . $upload_base . '/' . $image_meta['file'] . '\'); "'
          . ' data-ldc-aspect-ratio="' . ($image_meta['height'] / $image_meta['width']) . '"'
        ;
        // echo rp_dump($image_meta);
      }

      //// End the opening <LI> tag. 
      $out[count($out)-1] .= '>';

      $out[] = '      <div class="ldc-list-title">'; // CSS table-row
      $out[] = '        <div>'; // table-cell
      $out[] = '          <h4>' . get_the_title() . '</h4>';
      $out[] = '        </div>';
      $out[] = '      </div>';
      $out[] = '      <div class="ldc-list-excerpt">'; // CSS table-row
      $out[] = '        ' . ($post_link ? '<a href="' . $post_link . '" target="_blank">' : '<span>'); // table-cell
      $out[] = '          <div>'; // CSS table
      $out[] = '            <div>'; // CSS table-row
      $out[] = '              <p><span>'  . the_excerpt_max_charlength(140) . '</span></p>'; // CSS table-cell
      $out[] = '              <p class="ldc-arrow"><span class="dashicons dashicons-controls-play"></span></p>';
      $out[] = '            </div>';
      $out[] = '          </div>';
      $out[] = '        ' . ($post_link ? '</a>' : '</span>');
      $out[] = '      </div>';
      $out[] = '    </li>';
    };

  //// ...or show an HTML comment. 
  } else {

    $out[] = '    <!-- ' . rp_dev('No posts found', 'Nothing matches'
      .                      ' type:'      . $atts['type']
      .                      ' status:'    . $atts['status']
      .                      ' number:'    . $atts['number']
      .                      ' offset:'    . $atts['offset']
      . ($atts['category'] ? ' category:'  . $atts['category'] : '')
      . ' -->'
    );
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




//// 20150821^RP  Register the stylesheet and the clientside code. 
//// See `wp_enqueue_script/style()` in /sc/sc-ldc_list.php:ldc_list_shortcode()
//// https://codex.wordpress.org/Function_Reference/wp_register_script
add_action('wp_enqueue_scripts', 'ldc_list_register_deps');
function ldc_list_register_deps() {
  global $ldc_theme;
  $version = $ldc_theme->get('Version');

  //// 
  wp_register_script(
    'ldc_list_script', // $handle
    get_stylesheet_directory_uri() . '/library/sc/sc-ldc_list.js', // $src
    array('jquery'),   // $deps
    $version,          // $ver
    true               // $in_footer
  );

  //// Basic styling for '.ldc-list' elements. 
  wp_register_style(
    'ldc_list_style',  // $handle
    get_stylesheet_directory_uri() . '/library/sc/sc-ldc_list.css', // $src
    array(),           // $deps
    $version           // $ver
  );
}



//// 20150821^RP  Validate a shortcode attribute. 
//// Pass: `ldc_list_validate( 'nice,good'    , array('fair','nice','good') )`
//// Fail: `ldc_list_validate( 'good,bad,ugly', array('fair','nice','good') )`
//// Used by /sc/sc-ldc_list.php:ldc_list_shortcode()
function ldc_list_validate($att, $valid) {

  //// Convert a comma-delimited attribute to an array of sub-attributes. 
  $sub_atts = explode(',', $att);

  //// Validate each sub-attribute. 
  foreach ($sub_atts as $sub_att) {
    if (! in_array($sub_att, $valid) ) { return false; }
  }

  //// Nothing invalid found! 
  return true;
}




//// 20150821^RP  Deal with an invalid attribute. 
//// Usage: `ldc_list_defaultize($warnings, $atts, 'some-key','Some Default')`
//// Used by /sc/sc-ldc_list.php:ldc_list_shortcode()
function ldc_list_defaultize(&$warnings, &$atts, $att_name, $default) {

  //// Add a warning. 
  $warnings[] = "Attribute `$att_name` had invalid value. Used default '$default' instead";

  //// Ue the default value. 
  $atts[$att_name] = $default;

}




?>