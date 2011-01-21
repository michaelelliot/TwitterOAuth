<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>TwitterOAuth in PHP</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('a.twitter_btn').click(function() {
            $(this).css('background', 'url(<?php echo LIB_PATH ?>TwitterOAuth/twitter_btn.gif) no-repeat bottom left');
        });
    });
    </script>
    <style type="text/css">
    * {
        font-family:'Lucida Grande', sans-serif;
    }
    body {
        background-color: #3dadd6;
    }
    a.twitter_btn  {
        display: block;
        background: url(<?php echo LIB_PATH ?>TwitterOAuth/twitter_btn.gif) no-repeat top left;
        width: 151px;
        height: 25px;
    }
    </style>
</head>
<body>
    <p>
        <h2>TwitterOAuth PHP example</h2>
        <p><a href="index.php?clear_twitter">Clear session variables</a></p>
        <h3>Links:</h3>
        <ul>
            <li><a href='https://github.com/thermal/TwitterOAuth'>Source Code</a></li>
            <li><a href='https://github.com/thermal/TwitterOAuth/wiki/documentation'>Documentation</a></li>
            <li>Contact @<a href='https://twitter.com/michaelelliot'>michaelelliot</a></li>
            <li>Contact @<a href='https://twitter.com/michaelelliot'>abraham</a></li>
        </ul>
        <hr />
    </p>
    <p>
        <pre>
        <?php
        if (@!$_SESSION['twitter']['logged_in']) {
            ?>
            <a class="twitter_btn" href="redirect.php"></a>
            <?php
        } else {
            print "<pre>";
            print "Welcome " . TwitterOAuth::getUsername() . "!\n\n";
            //print TwitterOAuth::getProfileDescription();
            //print TwitterOAuth::getTweetCount();
            print_r($_SESSION);
            print "</pre>";
        }
        ?>
        </pre>
    </p>
</body>
</html>
