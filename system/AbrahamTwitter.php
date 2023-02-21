<?php

use Abraham\TwitterOAuth\TwitterOAuth;

class AbrahamTwitter
{
    private static $at;
    private static $connection;

    public static function getInstance()
    {
        if (self::$at == null) {
            self::$at = new AbrahamTwitter();
        }
        return self::$at;
    }

    public function __construct()
    {
        $config = $this->get_settings();
        self::$connection = new TwitterOAuth(
            $config['consumer_key'],
            $config['consumer_secret'],
            $config['oauth_access_token'],
            $config['oauth_access_token_secret']
        );
    }

    public function get_user_data($type, $value)
    {
        $data = '';
        switch ($type) {
            case 'id':
                $data = self::$connection->get('users/show', [
                    'user_id' => $value, 'include_entities' => 'true'
                ]);
                break;
            case 'un':
                $data = self::$connection->get('users/show', [
                    'screen_name' => $value, 'include_entities' => 'true'
                ]);
                break;
        }

        return $data;
    }

    public function get_status_data($tweet_id)
    {
        return self::$connection->get('statuses/show', [
            'id' => $tweet_id,
            'tweet_mode' => 'extended',
            'include_entities' => 'true'
        ]);
    }

    public function get_settings()
    {
        global $twitter_api;
        $ts = $twitter_api;
        return $ts[time() % sizeof($ts)];
    }
}
