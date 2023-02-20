  <?php
  require_once("config.php");

  $json = file_get_contents('php://input');
  $tg = Telegram::getInstance($json);
  $md = Methods::getInstance();
  $chat_id = $tg->getChatId();
  $text = $tg->getMessageText();

  if (strpos($text, "start") !== false) {
    // Start bot
    $md->setStartMessage($chat_id, $text);
  } elseif (strpos($text, "getlink-") !== false) {
    // No start bot
    $tg->setChatAction($chat_id);
    $data = explode("-", $text);
    $msg = "Twitter profile link: 👇🏽\n\n";
    if ($data[1] == 'id') {
      $msg .= "<code>https://twitter.com/intent/user?user_id=" . $data[2] . "</code>";
    } else {
      $msg .= "<code>https://twitter.com/intent/user?screen_name=" . $data[2] . "</code>";
    }
    $tg->sendMessage($chat_id, $msg);
  } elseif (strpos($text, "https") !== false) {
    // No start bot
    $tg->setChatAction($chat_id);
    $slash_count = substr_count($text, '/');
    $regex = '/^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$/m';
    if (preg_match_all($regex, $text, $matches, PREG_SET_ORDER)) {
      $data_field = $matches[0][6];
      switch ($slash_count) {
        case 3:
          $md->userIdLookUp($chat_id, "user", $data_field);
          break;

        case 5:
          $md->status_id_lookup($chat_id, $data_field);
          break;
      }
    }
  } elseif (strpos($text, "si:") !== false) {
    $data = explode(":", $text);
    $md->si_contents($chat_id, $data);
  } else {
    // No start bot
    $tg->setChatAction($chat_id);
    $type = "un";
    if (is_numeric($text))
      $type = "id";

    $md->userIdLookUp($chat_id, $type, $text);
  }
