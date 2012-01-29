<?php
require_once('LightOAuth2.php');
// sample for Google Latitude API using OAuth 2.0 draft
define('CLIENT_ID','<your client id>');
define('CLIENT_SECRET','<your client secret>');
// path for the application script (it should be registered on Google console)
define('CALLBACK','http://www.example.com/google_lat.php');

define('SCOPE','https://www.googleapis.com/auth/latitude.current.city https://www.googleapis.com/auth/latitude.current.best https://www.googleapis.com/auth/latitude.all.city https://www.googleapis.com/auth/latitude.all.best');
$entry = array('authorize'=>'https://accounts.google.com/o/oauth2/auth',
	       'access_token'=>'https://accounts.google.com/o/oauth2/token');

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
  $obj = $oauth->getToken($entry['access_token'], CALLBACK, $_GET['code']);
  $_SESSION['access_token'] = $obj->access_token;
}

// access to proteced resource
$oauth->setToken($_SESSION['access_token']);
$url = "https://www.googleapis.com/latitude/v1/currentLocation";
$response = $oauth->fetch($url);
$obj = json_decode($response);
echo "timestamp[ms]: ".$obj->data->timestampMs, "<br/>";
echo "latitude: ".$obj->data->latitude, "<br/>";
echo "longitude: ".$obj->data->longitude, "<br/>";
?>
