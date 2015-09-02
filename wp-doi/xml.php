<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// if (isset($_SERVER['HTTPS'])) {
//   $host = 'https://' . $_SERVER['HTTP_HOST'];
// }
// else {
//   $host = 'http://' . $_SERVER['HTTP_HOST'];
// }
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-includes/post.php');

if (isset($_GET['id'])) {
  echo 'Success! The post ID is: ' . $_GET['id'];
}
else {
  echo 'Error: No post ID supplied. Please include a post ID to view XML, eg. /wp-doi/xml.php?id=34';
}
?>