<?php
/**
 * @package WP_DOI
 * @version 0.0.1
 */
/*
Plugin Name: WP DOI
Plugin URI: http://example.com
Description: Registers DOIs with Crossref.
Author: Ian Hamilton
Version: 0.0.1
Author URI: http://colorcrate.com
*/

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

global $pagenow;

// Create the XML button
if( is_admin() && $pagenow === 'post.php' ) {
    createXMLButton();
}

function createXMLButton() {
  add_action( 'post_submitbox_misc_actions', 'xml_button' );

  function xml_button(){
    $postid = get_the_ID();
    $url = plugins_url() . '/wp-doi/xml.php?id=' . $postid;
    $html = '<div class="misc-pub-section" style="text-align: right;">';
    $html .= '<a class="button" href="' . $url . '" target="_blank">View Crossref XML</a>';
    $html .= '</div>';
    echo $html;
  }
}
?>