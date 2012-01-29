<?php
require_once('LightOAuth2.php');
// sample for Mixi Connect API using OAuth 2.0 draft 10
define('CLIENT_ID','<your client id>');
define('CLIENT_SECRET','<your client secret>');

$entry = array('authorize'=>'https://mixi.jp/connect_authorize.pl',
	       'access_token'=>'https://secure.mixi-platform.com/2/token');

// path for the application script'
define('CALLBACK','http://www.example.com/example_mixi.php');

// 'cainfo' should be specified if the default CA info isn't available.
//$copts = array('cainfo'=>'<path for local CA info file>'); 
//$oauth = new LightOAuth2(CLIENT_ID, CLIENT_SECRET, $copts);
$oauth = new LightOAuth2(CLIENT_ID, CLIENT_SECRET);
   
session_start();
if (!isset($_SESSION['access_token'])) {
  if (!isset($_GET['code'])) { // get authorization code
    $opts = array('scope'=>'r_updates r_voice','display'=>'pc');
    $url = $oauth->getAuthUrl($entry['authorize'], CALLBACK, $opts);
    header("Location: " . $url);
    exit();
  }
  // get access token
  $obj = $oauth->getToken($entry['access_token'], CALLBACK, $_GET['code']);
  $_SESSION['access_token'] = $obj->access_token;
}

// access to proteced resource
$oauth->setToken($_SESSION['access_token']);
$url = "http://api.mixi-platform.com/2/voice/statuses/friends_timeline/";
$obj = json_decode($oauth->fetch($url));
print_r($obj);
?>
