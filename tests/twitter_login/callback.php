<?php
require_once('config.php');
require_once('TwitterOAuth/TwitterOAuth.php');
session_start();

try {
    # Get the access token
    $access_token = @TwitterOAuth::handleCallback($_SESSION['twitter']['temp_oauth_token'], $_SESSION['twitter']['temp_oauth_token_secret']);
    if (!$access_token) throw new Exception("ACCESS_TOKEN_ERROR");
    # Remove no longer needed temporary request tokens
    unset($_SESSION['twitter']['temp_oauth_token']);
    unset($_SESSION['twitter']['temp_oauth_token_secret']);
    # Save the access token. Normally this would be saved to a database for future use
    # TODO: Save to database!
    $_SESSION['twitter']['logged_in'] = true;
    $_SESSION['twitter']['access_token'] = $access_token;
    # Get user credentials? (i.e. name, location, language, followers...)
    if (GET_CREDENTIALS) {
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['twitter']['access_token']['oauth_token'], $_SESSION['twitter']['access_token']['oauth_token_secret']);
        $_SESSION['twitter']['credentials'] = $connection->get('account/verify_credentials');
    } else {
        unset($_SESSION['twitter']['credentials']);
    }
} catch (Exception $e) {
    $_SESSION['twitter'] = null;
    die("Error: " . $e->getMessage());
}
header('Location: ./');
