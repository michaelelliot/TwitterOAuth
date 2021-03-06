<?php

/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * The first PHP Library to support OAuth for Twitter's REST API.
 */

/* Load OAuth lib. You can find it at http://oauth.net */
require_once('OAuth.php');

/**
 * Twitter OAuth class
 */
class TwitterOAuth {
  /* Contains the last HTTP status code returned. */
  public $http_code;
  /* Contains the last API call. */
  public $url;
  /* Set up the API root URL. */
  public $host = "https://api.twitter.com/1/";
  /* Set timeout default. */
  public $timeout = 30;
  /* Set connect timeout. */
  public $connecttimeout = 30; 
  /* Verify SSL Cert. */
  public $ssl_verifypeer = FALSE;
  /* Respons format. */
  public $format = 'json';
  /* Decode returned json data. */
  public $decode_json = TRUE;
  /* Contains the last HTTP headers returned. */
  public $http_info;
  /* Set the useragnet. */
  public $useragent = 'TwitterOAuth v0.2.0-beta2';
  /* Immediately retry the API call if the response was not successful. */
  //public $retry = TRUE;


/* Add this:
id (getTwitterId)
screen_name (getUsername)
name (getName)
description (getProfileDescription)
profile_image_url (getProfileImageUrl)
url (getProfileUrl)
lang (getLanguage)
location (getLocation)
time_zone (getTimezone)
statuses_count (getTweetCount)
followers_count (getFollowersCount)
friends_count (getFollowingCount)
listed_count (getListedCount)
 */
    public static function getTwitterId() {
        return @$_SESSION['twitter']['access_token']['user_id'];
    }
    public static function getUsername() {
        return @$_SESSION['twitter']['access_token']['screen_name'];
    }
    public static function getName() {
        return @$_SESSION['twitter']['credentials']['name'];
    }
    public static function getProfileDescription() {
        return @$_SESSION['twitter']['credentials']->description;
    }
    public static function getProfileImageUrl() {
        return @$_SESSION['twitter']['credentials']->profile_image_url;
    }
    public static function getProfileUrl() {
        return @$_SESSION['twitter']['credentials']->url;
    }
    public static function getLanguage() {
        return @$_SESSION['twitter']['credentials']->lang;
    }
    public static function getLocation() {
        return @$_SESSION['twitter']['credentials']->location;
    }
    public static function getTimezone() {
        return @$_SESSION['twitter']['credentials']->time_zone;
    }
    public static function getTweetCount() {
        return @$_SESSION['twitter']['credentials']->statuses_count;
    }
    public static function getFollowersCount() {
        return @$_SESSION['twitter']['credentials']->followers_count;
    }
    public static function getFollowingCount() {
        return @$_SESSION['twitter']['credentials']->friends_count;
    }
    public static function getListedCount() {
        return @$_SESSION['twitter']['credentials']->listed_count;
    }
    
    public static function handleCallback($oauth_token, $oauth_token_secret) {
        # Sanity checks
        if (empty($_REQUEST['oauth_token']) || empty($_REQUEST['oauth_verifier'])) {
            throw new Exception("INVALID_REQUEST");
        } else if ($_REQUEST['oauth_token'] != $oauth_token) {
            throw new Exception("TOKEN_EXPIRED");
        }
        # Create TwitteroAuth object with app key/secret and token key/secret
        if (!$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret)) {
            throw new Exception("ERROR_CONNECTING");
        }
        # Request access tokens from twitter
        if (!$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier'])) {
            throw new Exception("COULD_NOT_VERIFY");
        }
        return $access_token;
    }

    # Set API URLs
    function accessTokenURL()  { return 'https://api.twitter.com/oauth/access_token'; }
    function authenticateURL() { return 'https://twitter.com/oauth/authenticate'; }
    function authorizeURL()    { return 'https://twitter.com/oauth/authorize'; }
    function requestTokenURL() { return 'https://api.twitter.com/oauth/request_token'; }

    # Debug helpers
    function lastStatusCode() { return $this->http_status; }
    function lastAPICall()    { return $this->last_api_call; }

    # construct TwitterOAuth object
    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
        $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        if ($oauth_token && $oauth_token_secret) {
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
        } else {
            $this->token = NULL;
        }
    }

    # Get a request_token from Twitter
    # @returns a key/value array containing oauth_token and oauth_token_secret
    function getRequestToken($oauth_callback = NULL) {
        $parameters = array();
        if ($oauth_callback)  $parameters['oauth_callback'] = $oauth_callback;
        $request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
        $token = OAuthUtil::parse_parameters($request);
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    # Get the authorize URL
    function getAuthorizeURL($token, $sign_in_with_twitter = TRUE) {
        if (is_array($token))  $token = $token['oauth_token'];
        if (!$sign_in_with_twitter) {
            return $this->authorizeURL() . "?oauth_token={$token}";
        } else {
            return $this->authenticateURL() . "?oauth_token={$token}";
        }
    }

    # Exchange request token and secret for an access token and secret, to sign API calls.
    # @returns array("oauth_token" => "token",
    #                "oauth_token_secret" => "token-secret",
    #                "user_id" => "123456",
    #                "screen_name" => "blah")
    function getAccessToken($oauth_verifier = NULL) {
        $parameters = array();
        if ($oauth_verifier) $parameters['oauth_verifier'] = $oauth_verifier;
        $request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
        $token = OAuthUtil::parse_parameters($request);
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    #One time exchange of username and password for access token and secret.
    # @returns array("oauth_token" => "the-access-token",
    #                "oauth_token_secret" => "the-access-secret",
    #                "user_id" => "9436992",
    #                "screen_name" => "abraham",
    #                "x_auth_expires" => "0")
    function getXAuthToken($username, $password) {
    $parameters = array();
    $parameters['x_auth_username'] = $username;
    $parameters['x_auth_password'] = $password;
    $parameters['x_auth_mode'] = 'client_auth';
    $request = $this->oAuthRequest($this->accessTokenURL(), 'POST', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
    }

    # GET wrapper for oAuthRequest.
    function get($url, $parameters = array()) {
        return json_decode($this->oAuthRequest($url, 'GET', $parameters));
    }

    # POST wrapper for oAuthRequest.
    function post($url, $parameters = array()) {
        return json_decode($this->oAuthRequest($url, 'POST', $parameters));
    }
    
    # DELETE wrapper for oAuthReqeust.
    function delete($url, $parameters = array()) {
        return json_decode($this->oAuthRequest($url, 'DELETE', $parameters));
    }

    # Format and sign an OAuth / API request
    function oAuthRequest($url, $method, $parameters) {
        if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
            $url = "{$this->host}{$url}.{$this->format}";
        }
        $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
        $request->sign_request($this->sha1_method, $this->consumer, $this->token);
        switch ($method) {
            case 'GET':
                return $this->http($request->to_url(), 'GET');
            default:
                return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
        }
    }

    # Make an HTTP request
    # @return API results
    function http($url, $method, $postfields = NULL) {
        $this->http_info = array();
        # Initialize curl
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if ($postfields) curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if ($postfields) $url = "{$url}?{$postfields}";
                break;
        }

        curl_setopt($ci, CURLOPT_URL, $url);
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;
        curl_close ($ci);
        return $response;
    }

    # Get the header info to store
    function getHeader($ch, $header) {
        $i = strpos($header, ':');
        if ($i) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }
        return strlen($header);
    }
}
