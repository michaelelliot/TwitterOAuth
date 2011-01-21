<?php
require_once('config.php');
require_once('TwitterOAuth/TwitterOAuth.php');
session_start();

if (isset($_REQUEST['clear_twitter'])) $_SESSION['twitter'] = null;
include('html.inc');
