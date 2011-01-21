<?php
define('CONSUMER_KEY', '2KMZ22GAebECbnhqQa3lA');
define('CONSUMER_SECRET', 'HTQP349Z1HfZdPfxt4a4NKWSj1tJhAQQSOs1PNCkqs');
define('OAUTH_CALLBACK_FILE', 'callback.php');
define('OAUTH_CALLBACK_URL', (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strpos(strrev($_SERVER['REQUEST_URI']), "/")) . OAUTH_CALLBACK_FILE : "http://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strpos(strrev($_SERVER['REQUEST_URI']), "/")) . OAUTH_CALLBACK_FILE);
define('LIB_PATH', '../../src/');
define('GET_CREDENTIALS', TRUE);
set_include_path(__DIR__ . '/' . LIB_PATH);
if (CONSUMER_KEY === '' || CONSUMER_SECRET === '') die('You need a consumer key and secret to use this example. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a> and then configure config.php correctly.');
?>