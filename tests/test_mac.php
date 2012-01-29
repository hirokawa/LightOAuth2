<?php
// from OAuth Mac draft 2
// http://tools.ietf.org/html/draft-hammer-oauth-v2-mac-token-01
// $digest_ref = 'kDZvddkndxvhGRXZhvuDjEWhGeE=';
require_once('LightOAuth2.php');

$secret = '489dks293j39';
$token = 'h480djs93hd8';
$timestamp = '137131200';
$nonce = 'dj83hs9s';
$url = 'http://example.com/resource/1?b=1&a=2';

$oauth = new LightOAuth2('','');
echo $oauth->getAuthHeader($url, $token),"\n"; // draft 10
$oauth->setCredentials('MAC',$secret,'hmac-sha-1');
echo $oauth->getAuthHeader($url, $token, HTTP_METH_GET, $timestamp, $nonce);
?>
