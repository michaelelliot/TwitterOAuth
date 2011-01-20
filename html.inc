<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Twitter OAuth in PHP</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <style type="text/css">
    img {border-width: 0}
    * {font-family:'Lucida Grande', sans-serif;}
    </style>
</head>
<body>
    <p>
        <h2>TwitterOAuthV2 PHP example</h2>
        <p><a href="index.php?clear">Clear session</a></p>
        <h3>Links:</h3>
        <ul>
            <li><a href='http://github.com/thermal/twitteroauthv2'>Source Code</a></li>
            <li><a href='http://wiki.github.com/thermal/twitteroauthv2/documentation'>Documentation</a></li>
            <li>Contact @<a href='http://twitter.com/michaelelliot'>michaelelliot</a></li>
        </ul>
        <hr />
    </p>
    <p>
        <pre>
        <?php
        if (isset($content)) {
            if (is_array($content)) {
                print_r($content);
            } else {
                print $content;
            }
        }
        ?>
        </pre>
    </p>
</body>
</html>
