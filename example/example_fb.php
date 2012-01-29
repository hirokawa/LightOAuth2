<?php
require_once('LightOAuth2.php');
// sample for Facebook Graph API using OAuth 2.0 draft
define('CLIENT_ID','<your client id>');
define('CLIENT_SECRET','<your client secret>');

define('SCOPE','read_stream,offline_access,publish_stream');
$entry = array('authorize'=>'https://graph.facebook.com/oauth/authorize',
	       'access_token'=>'https://graph.facebook.com/oauth/access_token');

// path for the application script'
define('CALLBACK','http://www.example.com/example_fb.php');

// 'cainfo' should be specified if the default CA info isn't available.
//$copts = array('cainfo'=>'<path for local CA info file>'); 
//$oauth = new LightOAuth2(CLIENT_ID, CLIENT_SECRET, $copts);
$oauth = new LightOAuth2(CLIENT_ID, CLIENT_SECRET);
   
session_start();
if (!isset($_SESSION['access_token'])) {
  if (!isset($_GET['code'])) { // get authorization code
    $opts = array('scope'=>SCOPE);
    $url = $oauth->getAuthUrl($entry['authorize'], CALLBACK, $opts);
    header("Location: " . $url);
    exit();
  }
  // get access token
  $obj = $oauth->getToken($entry['access_token'], CALLBACK, $_GET['code'], 'url');
  $_SESSION['access_token'] = $obj->access_token;
}

// access to proteced resource
$oauth->setToken($_SESSION['access_token']);
$url = "https://graph.facebook.com/me/feed";
$response = $oauth->fetch($url);
$obj = json_decode($response);
print_r($obj);
?>
