<?php

require_once("system/twitteroauth/autoload.php");
require_once("system/AbrahamTwitter.php");
require_once("system/Telegram.php");
require_once("system/Database.php");
require_once("system/Methods.php");

const _TOKEN = "bot-token";
const _CHANNEL_ARCHIVE = "chat-id";
const _CHANNEL_REPORTS = "chat-id";

global $config;
$config['host'] = "localhost";
$config['user'] = "username";
$config['pass'] = "password";
$config['name'] = "database-name";

global $twitter_api;
$twitter_api = array(
    array(
        'crname' => '01',
        'consumer_key' => '***',
        'consumer_secret' => '***',
        'oauth_access_token' => '***',
        'oauth_access_token_secret' => '***'
    ),
    array(
        'crname' => '02',
        'consumer_key' => '***',
        'consumer_secret' => '***',
        'oauth_access_token' => '***',
        'oauth_access_token_secret' => '***'
    )
);
