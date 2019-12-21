<?php
/*
Plugin Name: Events Layout
Plugin URI: https://github.com/paulprogrammer/wp-events-layout
Description: Does some event layout tasks
Version: 0.0.0
Author: Paul Williams
Author URI: https://github.com/paulprogrammer
*/

function wpel_display_event($atts = []) {
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  $query = new WP_Query( array(
    'post_type' => 'event',
    'tax_query' => array( array( 
      'taxonomy' => 'event-categories',
      'field' => 'slug',
      'terms' => 'tournament'
    )),
    'order' => 'ASC',
    'orderby' => 'date-time',
  ) );
  
  if ( $query->have_posts() ) {
    echo '<ul>';
    while ( $query->have_posts() ) {
      $query->the_post();
      echo '<li>' . get_the_title() . '</li>';
    }
    echo '</ul>';
  } else {
      // no posts found
  }
  
  // clean up after my query mess
  wp_reset_postdata();
}

function wpel_shortcodes_init() {
  add_shortcode( 'event_display', 'wpel_display_event');
}
 
add_action('init', 'wpel_shortcodes_init');
