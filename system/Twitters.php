<?php


class Twitter
{

  private static $twttr;
  private static $tae;
  private static $crname;

  public static function getInstance()
  {
    if (self::$twttr == null) {
      self::$twttr = new Twitter();
    }
    return self::$twttr;
  }

  private function __construct()
  {
    if (self::$tae == null) {
      self::$tae = new TwitterAPIExchange($this->get_settings());
    }
  }

  public function getUserData($type, $value)
  {
    $url = 'https://api.twitter.com/1.1/users/show.json';

    if ($type == "id") {
      $getField = '?user_id=' . $value;
    } else {
      $getField = '?screen_name=' . $value;
    }

    $result = array();
    $result['core'] = self::$crname;
    $result['data'] = self::$tae->setGetfield($getField)
      ->buildOauth($url, 'GET')
      ->performRequest();
    return $result;
  }

  public function get_status_data($si)
  {
    $url = 'https://api.twitter.com/1.1/statuses/show.json';
    $getField = '?id=' . $si;
    return self::$tae->setGetfield($getField)
      ->buildOauth($url, 'GET')
      ->performRequest();
  }

  public function get_settings()
  {
    global $twitter_api;
    $setting = $twitter_api[time() % sizeof($twitter_api)];
    self::$crname = $setting['crname'];
    return $twitter_api[time() % sizeof($twitter_api)];
  }
}
