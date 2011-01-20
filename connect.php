<?php
/* 
 * Check if consumer token is set and if so send user to get a request token.
 */

# Exit with an error message if the CONSUMER_KEY or CONSUMER_SECRET is not defined
require_once('config.php');
if (CONSUMER_KEY === '' || CONSUMER_SECRET === '') {
    $content = 'You need a consumer key and secret to use this example. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a> and then configure config.php correctly.';
} else {
    $content = TWITTER_BUTTON;
}
 
# Render page
include('html.inc');
