<?php
/**
 * Twenty Twelve functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

date_default_timezone_set('America/Chicago');

add_filter('is_multi_author','__return_true');

function fix_links($input) { return preg_replace('!http(s)?://' . $_SERVER['SERVER_NAME'] . '/!', '/', $input);}

function your_thumbnail_caption($html, $post_id, $post_thumbnail_id, $size, $attr)
  {
  $attachment =& get_post($post_thumbnail_id);

  if ($attachment->post_excerpt || $attachment->post_content) {
  $html .= '<p class="thumbcaption">';
  if ($attachment->post_excerpt) {
    $html .= '<span class="captitle">'.$attachment->post_excerpt.'</span> ';
  }
  $html .= $attachment->post_content.'</p>';
  }

  return $html;
}

add_action('post_thumbnail_html', 'your_thumbnail_caption', null, 5);
