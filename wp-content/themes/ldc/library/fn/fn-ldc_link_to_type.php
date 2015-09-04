<?php

//// 20150814^RP  Convert a link URL like https://twitter.com/foo/status/123 to 
//// a type like 'Twitter'. 
//// Used by /library/cpt/cpt-chatter.php:ldc_chatter_table_content()
//// Used by /library/cpt/cpt-creation.php:ldc_creation_table_content()
//// Used by /library/cpt/cpt-event.php:ldc_event_table_content()
//// Used by /library/sc/sc-ldc_list.php:ldc_list_shortcode()
function ldc_link_to_type($link) {

  //// Typically 'Chatter' links. 
  if ( strpos($link, '://twitter.com/') )              { return 'Twitter';    } // .ldc-link-type-twitter
  if ( strpos($link, '://facebook.com/') )             { return 'Facebook';   } // .ldc-link-type-facebook
  if ( strpos($link, '://www.facebook.com/') )         { return 'Facebook';   } // .ldc-link-type-facebook
  if ( strpos($link, '.tumblr.com/') )                 { return 'Tumblr';     } // .ldc-link-type-tumblr
  if ( strpos($link, '.pinterest.com/') )              { return 'Pinterest';  } // .ldc-link-type-pinterest

  //// Typically 'Event' links. 
  if ( strpos($link, '://www.eventbrite.co.uk/') )     { return 'Eventbrite'; } // .ldc-link-type-eventbrite
  if ( strpos($link, '://vimeo.com/') )                { return 'Vimeo';      } // .ldc-link-type-vimeo
  if ( strpos($link, '://www.meetup.com/') )           { return 'Meetup';     } // .ldc-link-type-meetup

  //// Typically 'Creation' links. 
  if ( strpos($link, '.loop.coop/') )                  { return 'Loop.Coop';  } // .ldc-link-type-loopcoop
  if ( strpos($link, '.richplastow.com/') )            { return 'rpOS';       } // .ldc-link-type-rpos
  if ( strpos($link, '.meteor.com/') )                 { return 'Meteor';     } // .ldc-link-type-meteor

  //// Not recognised!
  return 'Other';
}


