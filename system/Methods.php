<?php


class Methods
{
    private static $m;
    private static $db;
    private static $tg;
    private static $ta;

    public static function getInstance()
    {
        if (self::$m == null) {
            self::$m = new Methods();
        }
        return self::$m;
    }

    public function __construct()
    {
        self::$db = Database::getInstance();
        self::$tg = Telegram::getInstance();
        self::$ta = AbrahamTwitter::getInstance();
    }

    public function setStartMessage($chat_id, $text)
    {
        $msg =  "Chat Id: <code>" . $chat_id . "</code>\n";
        $msg .= "Data: <code>" . $text . "</code>\n";
        self::$tg->sendMessage(_CHANNEL_REPORTS, $msg);

        self::$tg->setChatAction($chat_id);
        self::$db->insertUserData($chat_id);
        if (strlen($text) > 7) {
            $data = str_replace('/start ', '', $text);
            $type = "un";
            if (is_numeric($data))
                $type = "id";
            $this->userIdLookUp($chat_id, $type, $data);
        } else {
            $text = "Please enter twitter username (like <code>jack</code>) or user-id (like <code>12</code>)\n";
            self::$tg->sendMessage($chat_id, $text);
        }
    }

    public function setTimestamp($userDate)
    {
        $date = explode(" ", $userDate);
        switch ($date[1]) {
            case "Jan":
                $month = "01";
                break;
            case "Feb":
                $month = "02";
                break;
            case "Mar":
                $month = "03";
                break;
            case "Apr":
                $month = "04";
                break;
            case "May":
                $month = "05";
                break;
            case "Jun":
                $month = "06";
                break;
            case "Jul":
                $month = "07";
                break;
            case "Aug":
                $month = "08";
                break;
            case "Sep":
                $month = "09";
                break;
            case "Oct":
                $month = "10";
                break;
            case "Nov":
                $month = "11";
                break;
            case "Dec":
                $month = "12";
                break;
        }

        $result = strtotime($date[3] . " " . $date[5] . "/" . $month . "/" . $date[2]);
        return $result;
    }

    public function setTwitterTimeArray($time)
    {
        $result = array();
        $timeStamp = $this->setTimestamp($time);
        $age = time() - $timeStamp;
        $result['d'] = number_format(floor($age / 86400));
        $result['h'] = $this->setNumber(floor(($age % 86400) / 3600));
        $result['i'] = $this->setNumber(floor(($age % 3600) / 60));
        $result['time'] = date('H:i:s', $timeStamp);
        $result['date'] = date('Y-m-d', $timeStamp);
        $result['ts'] = $age;
        return $result;
    }

    public function setUserMessageBody($type, $value)
    {
        $result = array();
        $data = self::$ta->get_user_data($type, $value);
        //$myfile = fopen($value . ".txt", "w");
        //fwrite($myfile, json_encode($data));
        //fclose($myfile);
        if ($data->screen_name != null) {
            $result['status'] = true;
            $part01 = "Twitter ID: <code>" . $data->id . "</code> \n";
            $part01 .= "Username: <code>" . $data->screen_name . "</code> \n";
            $part01 .= "Name: " . $data->name . " \n";
            $part01 .= "Bio: " . $data->description . "\n";

            if (isset($data->entities->url->urls[0]->expanded_url))
                $part01 .= "Link: " . $data->entities->url->urls[0]->expanded_url . "\n";

            if (isset($data->location))
                $part01 .= "Location: <code>" . $data->location . "</code> \n";

            $part01 .= "Following: " . number_format($data->friends_count) . " \n";
            $part01 .= "Followers: " . number_format($data->followers_count) . " \n";
            $part01 .= "Listed: " . number_format($data->listed_count) . " \n";
            $part01 .= "Tweets: " . number_format($data->statuses_count) . " \n";
            $part01 .= "Favorite: " . number_format($data->favourites_count) . "\n";
            $part01 .= "Verified: " . ($data->verified == true ? "Yes" : "No") . " · ";
            $part01 .= "Protected: " . ($data->protected == true ? "Yes" : "No") . "\n";
            $part01 .= "Device: " . ($data->status->source) . "\n \n";

            $last_tweet = $this->setTwitterTimeArray($data->status->created_at);
            $part01 .= "Last tweet: " . $last_tweet['d'] . " days, " . $last_tweet['h'] . ":" . $last_tweet['i'] . " ago\n";
            $part01 .= "· <code>" . $last_tweet['date'] . ", " . $last_tweet['time'] . "</code> UTC\n \n";

            $time = $this->setTwitterTimeArray($data->created_at);
            $part01 .= "Created at: " . $time['d'] . " days, " . $time['h'] . ":" . $time['i'] . " ago\n";
            $part01 .= "· <code>" . $time['date'] . ", " . $time['time'] . "</code> UTC\n \n";

            $part02 = "Twitter ID: #i" . $data->id . "\n";
            $part02 .= "Check again: <a href='https://t.me/TwitterProfileBot?start=" . $data->id . "'>StartBot</a> · ";
            $part02 .= "Show <a href='https://twitter.com/intent/user?user_id=" . $data->id . "'>Profile</a> \n\n";

            $part03 = "Username: #" . $data->screen_name . "\n";
            $part03 .= "Check again: <a href='https://t.me/TwitterProfileBot?start=" . $data->screen_name . "'>StartBot</a> · ";
            $part03 .= "Show <a href='https://twitter.com/intent/user?screen_name=" . $data->screen_name . "'>Profile</a> \n\n";

            $result['data'][1] = $part01;
            $result['data'][2] = $part02;
            $result['data'][3] = $part03;
            $result['btn']['id'] = $data->id;
            $result['btn']['user'] = $data->screen_name;
            $result['profile'] = str_replace("_normal", "", $data->profile_image_url_https);
        } else {
            $result['status'] = false;
            $result['data'] = null;
        }
        return $result;
    }

    public function userIdLookUp($chatId, $type, $text)
    {
        $data = $this->setUserMessageBody($type, $text);
        if ($data['status']) {
            $channel_msg = $data['data'][1];
            $channel_msg .= $data['data'][2];
            $channel_msg .= $data['data'][3];
            self::$tg->sendPhoto(_CHANNEL_ARCHIVE, $data['profile'], $channel_msg);

            if ($chatId != "2052399630") {
                $user_msg = $data['data'][1];
                $user_msg .= $data['data'][2];
                $user_msg .= $data['data'][3];
                $user_msg .= 'Prepared by: @TwitterProfileBot';
                self::$tg->sendUserInfoMessageButton(
                    $chatId,
                    $data['profile'],
                    $user_msg,
                    $data['btn']['id'],
                    $data['btn']['user']
                );
            }
        } else {
            $message = "Entrance: <code>" . $text . "</code> \n";
            $message .= "User not found!";
            $body[0]['text'] = "Check Again";
            $body[0]['callback_data'] = $text;
            $buttons = array('body' => $body, 'bodyVertical' => 1);
            self::$tg->sendInlineKeyboard($chatId, $message, "text", null, $buttons);
        }
    }

    public function status_id_lookup($chatId, $id)
    {
        $data = self::$ta->get_status_data($id);
        self::$db->insert_tweet_json($id, json_encode($data));

        $user = "◉ <a href='https://twitter.com/i/status/" . $id . "'>@" . $data->user->screen_name . "</a> (" . $data->user->name . ")";
        $text = preg_replace("@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@", '', $data->full_text);
        $ts = $this->setTimestamp($data->created_at);
        if (isset($data->extended_entities)) {
            // Tweet with media
            $media = $data->extended_entities->media;
            //self::$tg->sendMessage($chatId, json_encode($media));
            if ($media[0]->type == "photo") {
                // Tweet with photo
                if (sizeof($media) == 1) {
                    # Tweet with one image
                    $msg = $user . "\n \n";
                    $msg .= $text . "\n \n";
                    $msg .= date('g:i A · M j, Y', $ts) . " · " . $data->source;
                    self::$tg->sendPhoto($chatId, $media[0]->media_url, $msg);
                } else {
                    # Tweet with more than one image
                    $media_list = array();
                    for ($i = 0; $i < sizeof($media); $i++) {
                        self::$tg->sendMessage($chatId, "- " . $media[$i]->media_url);
                        $media_list[] = array(
                            'type' => 'photo',
                            'media' => $media[$i]->media_url,
                        );
                    }

                    $msg = $user . "\n \n";
                    $msg .= $text . "\n \n";
                    $msg .= date('g:i A · M j, Y', $ts) . " · " . $data->source;

                    // $media_list[count($media_list) - 1]['caption'] = urlencode($msg);
                    $media_list[0]['caption'] = urlencode($msg);
                    $media_list[0]['parse_mode'] = 'html';
                    self::$tg->send_media_group($chatId, json_encode($media_list));
                }
            } elseif ($media[0]->type == "video") {
                // Tweet with video
                $msg = $user . "\n \n";
                $msg .= $text . "\n \n";
                $msg .= date('g:i A · M j, Y', $ts) . " · " . $data->source;

                $video = $media[0]->video_info->variants[0]->url;
                self::$tg->sendVideo($chatId, $video, $msg);
            }
        } else {
            $msg = $user . "\n \n";
            $msg .= $text . "\n \n";
            // 8:19 PM · Jan 11, 2023 · Twitter for iPhone
            $msg .= date('g:i A · M j, Y', $ts) . " · " . $data->source;
            self::$tg->sendMessage($chatId, $msg);
        }
    }

    public function si_contents($chat_id, $data)
    {
        switch ($data[2]) {
            case "device":
                $td = self::$ta->get_status_data($data[1]);
                $sd = json_decode($td, true);
                $time = $this->setTwitterTimeArray($sd['created_at']);
                $msg = "username: <a href='https://t.me/TwitterProfileBot?start=" . $sd['user']['id'] . "'>@" . $sd['user']['screen_name'] . "</a>\n";
                $msg .= "created: <code>" . $time['date'] . " " . $time['time'] . " UTC</code>\n";
                $msg .= "device: " . $sd['source'] . "\n";
                self::$tg->sendMessage($chat_id, $msg);
                break;
        }
    }

    public function setNumber($number)
    {
        if ($number > 9) {
            return $number;
        } else {
            return "0" . $number;
        }
    }
}
