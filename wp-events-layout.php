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

  // first search for posts from the 'events' plugin
  $query = new WP_Query( array(
    'post_type' => 'event',
    'scope' => 'future',
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
      $post = get_post();
      echo '<li>' . get_post_permalink($post) . get_the_post_thumbnail($post) . get_the_title() . ': ' . $post->_event_start . '</li>';
    }
    echo '</ul>';
  }

  // now search for events from the 'mep_events' plugin
  $query = new WP_Query( array(
    'post_type' => 'mep_events',
    'tax_query' => array( array(
      'taxonomy' => 'mep_cat',
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
      $post = get_post();
      echo '<li>' . get_post_permalink($post) . get_the_post_thumbnail($post) . get_the_title() . ': ' . $post->mep_event_start_date . '</li>';
    }
    echo '</ul>';
  }
  
  // clean up after my query mess
  wp_reset_postdata();
}

function wpel_shortcodes_init() {
  add_shortcode( 'event_display', 'wpel_display_event');
}
 
add_action('init', 'wpel_shortcodes_init');
