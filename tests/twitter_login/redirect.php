<?php
require_once('config.php');
require_once('TwitterOAuth/TwitterOAuth.php');
session_start();

# Build TwitterOAuth object and get temporary credentials
$oauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $oauth->getRequestToken(OAUTH_CALLBACK_URL);
if (!$request_token || $oauth->http_code != 200) {
    header("Location: ?twitter_auth_error");
    exit;
}
# Save temporary credentials to session
$_SESSION['twitter']['temp_oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['twitter']['temp_oauth_token_secret'] = $request_token['oauth_token_secret'];
# Build authorize URL and redirect user to Twitter
$url = $oauth->getAuthorizeURL($token);
header("Location: $url");
