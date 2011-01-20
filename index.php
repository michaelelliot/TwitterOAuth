<?php
/**
 * @file
 * 
 */
session_start();
require_once('twitteroauthv2/twitteroauthv2.php');
require_once('config.php');

# If access token not available clear session variables and redirect to connect page
if (empty($_SESSION['access_token']) || isset($_REQUEST['clear'])) {
    session_unset();
    header('Location: connect.php');
    exit;
}

if (empty($_SESSION['credentials'])) {
    # Create a TwitterOauth object with consumer/user tokens
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    # Get user Twitter credentials
    $_SESSION['credentials'] = $connection->get('account/verify_credentials');
    # Some example calls
    # $connection->get('users/show', array('screen_name' => 'blah')));
    # $connection->post('statuses/update', array('status' => date(DATE_RFC822)));
    # $connection->post('statuses/destroy', array('id' => 1234567890));
    # $connection->post('friendships/create', array('id' => 12345)));
    # $connection->post('friendships/destroy', array('id' => 12345)));
    $content = $_SESSION;
} else {
    $content = $_SESSION;
}

# Include HTML to display on the page
include('html.inc');
