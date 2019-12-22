<?php
/*
Plugin Name: Events Layout
Plugin URI: https://github.com/paulprogrammer/wp-events-layout
Description: Does some event layout tasks
Version: 0.0.0
Author: Paul Williams
Author URI: https://github.com/paulprogrammer
*/

function wpel_query_event () {
  $events = array();

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
    while ( $query->have_posts() ) {
      $postdata = array();

      $query->the_post();
      $post = get_post();

      $postdata['permalink'] = get_post_permalink($post);
      $postdata['thumbnail'] = get_the_post_thumbnail($post);
      $postdata['title'] = get_the_title();
      $postdata['date'] = date_create_from_format( 'Y-m-d H:i:s', $post->_event_start);

      $events[] = $postdata;
    }
  }

  return $events;
}

function wpel_query_mep_events () {
  $events = array();

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
    while ( $query->have_posts() ) {
      $postdata = array();

      $query->the_post();
      $post = get_post();

      $postdata['permalink'] = get_post_permalink($post);
      $postdata['thumbnail'] = get_the_post_thumbnail($post);
      $postdata['title'] = get_the_title();
      $postdata['date'] = date_create_from_format( 'Y-m-d H:i', $post->mep_event_start_date );

      $events[] = $postdata; 
    }
  }

  return $events;
}

function date_comparison($a, $b) {
  if( $a['date'] == $b['date']) {
    return 0;
  }
  return $a['date'] < $b['date'] ? -1 : 1;
}

function wpel_display_event($atts = []) {
  // enqueue our CSS for display...
  wp_enqueue_style('source-code-pro', 'https://fonts.googleapis.com/css?family=Source+Code+Pro:400,700,900&display=swap');
  wp_enqueue_style('wpel-style', plugin_dir_url(__FILE__).'style.css');

  // first search for posts from the 'events' plugin
  $events = wpel_query_event();

  // now search for events from the 'mep_events' plugin
  $events = array_merge($events, wpel_query_mep_events());

  // sort by date in the value
  uasort($events, 'date_comparison');

  echo "<div class='events-container'>";
  foreach( $events as $event) {
    echo "<a href='".$event['permalink']."'>";
    echo "<div class='event'>";
    echo "<div class='link'><a href='".$event['permalink']."'>".$event['title']."</a></div>";
    echo "<div class='thumbnail'>".$event['thumbnail']."</div>";
    $month = date_format($event['date'], 'M');
    $day = date_format($event['date'], 'd');
    $year = date_format($event['date'], 'Y');
    echo "<div class='date'><span class='month'>$month</span><span class='day'>$day</span><span class='year'>$year</span></div>";
    echo "</div>";
    echo "</a>";
  }
  echo "</div>";

  // clean up after my query mess
  wp_reset_postdata();
}

function wpel_shortcodes_init() {
  add_shortcode( 'event_display', 'wpel_display_event');
}
 
add_action('init', 'wpel_shortcodes_init');
