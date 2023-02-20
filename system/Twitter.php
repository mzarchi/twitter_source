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
    $ts = array(
      array(
        'crname' => 'cr11',
        'consumer_key' => 'vfEO4ZD4AXlDfvoLzZueVBcQM',
        'consumer_secret' => 'OJ5trSeKu6aK3LPygbKVCQBdmwWAWIdllm3JAGEMAfvmpvwfJR',
        'oauth_access_token' => '1493931614744854534-6X90Q4bSkNyWOYXBRV06YyIpVmA5sG',
        'oauth_access_token_secret' => 'NlQRg59hjdhaZBkUNbKMwHxKY0o14vZpZTicZITbzHLk3'
      ),
      array(
        'crname' => 'cr12',
        'consumer_key' => 'a3FxV0ERdGO7l2vTUMU3QutPW',
        'consumer_secret' => 'ADjWr44wMrBlIlrdPioBWMSnyVuTVtDMSnv8CoyJKkE2lGC232',
        'oauth_access_token' => '1493931614744854534-jHe4bDzdwnBcIVJ4uWZZXHFLAtdYag',
        'oauth_access_token_secret' => '5btRCUnkZm9fD2MR9qZpfzYIEVyjMt4RAlbzNC7ht4OcN'
      ),
      array(
        'crname' => 'cr21',
        'consumer_key' => 'XXtQGUzlyAsRbAIFHGjkq1RNI',
        'consumer_secret' => 'A7LD43r7aVholGOsiRar0H5hfpJBi056Ch8IV5RBbZLtBZ0WT6',
        'oauth_access_token' => '1493942493137805314-PkrnlxTECXbrRa9zEdGDSJ2MDi1zDb',
        'oauth_access_token_secret' => 'Qxv9mLX8lfpVvXI2YB5n5QMHxUTDQReSPU5HAjXrZlC4i'
      ),
      array(
        'crname' => 'cr22',
        'consumer_key' => 'WPBQw6EZuGeD2ETtQHCodyzXb',
        'consumer_secret' => 'SZ1KXeBUKivas6cLI95Y0m1XbNHsRQVkQTfGyn2yebxqA7fVLz',
        'oauth_access_token' => '1493942493137805314-F8At9oxhz5NSRLRpgqAl6gubvme1yx',
        'oauth_access_token_secret' => '3FUFxAiWHF4Wnoh3BknjjR1JJr0SOMHBqx7nz74ecYwFq'
      ),
      array(
        'crname' => 'cr31',
        'consumer_key' => 'GmAsRtJ5e6xjpZMKskADzIzMQ',
        'consumer_secret' => 'Qbajnw3xILaKtXEk1mG0jW5bdIMOdMGuQKhxmpHjtjZubKVMyq',
        'oauth_access_token' => '1443816451622772736-HTgUV6ByiOEL27CFGUB4fxYqXuoLTJ',
        'oauth_access_token_secret' => 'yrJEokv6tyu8mnRJ3Gf4ht9nMf0kErgu0ToBrFuRSENfG'
      ),
      array(
        'crname' => 'cr32',
        'consumer_key' => '5TytlKNgC15HtYGfVTXJfsGg8',
        'consumer_secret' => 'cl52MhcQxk20Gqmk6BHXgFJNWf6XxWISWPbTkmu7IO5Duxy5ws',
        'oauth_access_token' => '1443816451622772736-YU76ztq0ieSeXDFKWW4Jfz5Z7wCSOP',
        'oauth_access_token_secret' => 'ptsNtCi3YyUBXWFwMai9L6W0o1YfPCnv224Yg0WnRhcKY'
      ),
      array(
        'crname' => 'cr41',
        'consumer_key' => 'B8zV40LAZKmOqkEfWIpYTOVlr',
        'consumer_secret' => 'lCy26lWit7Nenb6E9xS0n7OOGFACxjyz8f23hwz9azj8f7Z3x2',
        'oauth_access_token' => '1478352645572239369-HWgkxzAeUL5IaKvSzTy9DMBcYusG1m',
        'oauth_access_token_secret' => 'iUAS0kkGtPahCmfh2Wpn45yUDwMQUzbDcum70JdMSJcBa'
      ),
      array(
        'crname' => 'cr51',
        'consumer_key' => 'ZJBakKCj6sgqgi08Rv8aLxCYU',
        'consumer_secret' => '21v0k0KBnK8ccFp1vfROUX3N028Gk2fzHvC8MJgSc4GMvzAUg7',
        'oauth_access_token' => '1409536476652711939-m9cGE6Ve9fbdYpGePUiB6gK2wL6ufH',
        'oauth_access_token_secret' => 'bvpjQqHXZB5F39fKPcg7jVQMax9WaSP6CFXRtToFHbzB4'
      ),
      array(
        'crname' => 'mo01',
        'consumer_key' => 'YLcBQMSLFCHoJcGs5XLWZCfBC',
        'consumer_secret' => 'oTFtiAejKDQ8MkbJTimDmpCZHVstyg8mIDK6kZz80cifmgIaWJ',
        'oauth_access_token' => '1069049102317613056-wfSMqjCKxfrWAjUiG0Wrf9cIo3ghh4',
        'oauth_access_token_secret' => 'gHeSGMFWOg28L4WhjpdacWzTEE1XJVLwBlcAow3eOT8X6'
      ),
      array(
        'crname' => 'mo02',
        'consumer_key' => 'naEJnW5u1tF95ZF4XMpsdQQZg',
        'consumer_secret' => 'e8n3IPKtbx6UDX4XerTzyqhvI0GPU2RlZd7GKR9Hry7K6fXirG',
        'oauth_access_token' => '1559678984048488448-lcwE6bZRObscwjUQHb7nH1mHX2ckrD',
        'oauth_access_token_secret' => 'O7EPUCM3UkWXO3B2te31XuPVHnzddqBmmRLAFkb7gW6hS'
      ),
      array(
        'crname' => 'mo03',
        'consumer_key' => 'jfXW8QuAPrjeQW9I424BgNYq5',
        'consumer_secret' => 'uWHFoL1wkpkQja5rRKYzevNzOWJIJJmmFnYVJ4z93raoM3qLSy',
        'oauth_access_token' => '1449837512625696771-TTDrO172a292jCQ8voa2U1CbXqh1kr',
        'oauth_access_token_secret' => '3tWDyFzxTs8rRfYVVtPfLkONEQgNo2CM0Fn1rWZvA4kGu'
      )
    );

    $setting = $ts[time() % sizeof($ts)];
    self::$crname = $setting['crname'];
    return $ts[time() % sizeof($ts)];
  }
}
