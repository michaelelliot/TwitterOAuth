<?php
/*
 * Callback page that is called after Twitter login. Verify credentials and redirect.
 */

session_start();
require_once('twitteroauthv2/twitteroauthv2.php');
require_once('config.php');

# If the oauth_token is old redirect to the connect page
if (isset($_REQUEST['oauth_token']) && isset($_SESSION['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    session_unset();
    header('Location: connect.php?error=old_token');
    exit;
}

# Sanity check
if (empty($_SESSION['oauth_token']) || empty($_SESSION['oauth_token_secret']) || empty($_REQUEST['oauth_verifier'])) {
    session_unset();
    header('Location: connect.php?error=sanity_fail');
    exit;
}

# Create TwitteroAuth object with app key/secret and token key/secret from default phase
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

# Request access tokens from twitter
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

# If something went wrong, redirect to connect page
if (!$access_token) {
    session_unset();
    header('Location: connect.php?error=unknown');
    exit;
}

# Save the access tokens. Normally these would be saved to a database for future use
# TODO: Save tokens to database!
$_SESSION['access_token'] = $access_token;

# Remove no longer needed request tokens
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

# The user has been verified and the access tokens can be saved for future use
$_SESSION['status'] = 'verified';
header('Location: index.php');
