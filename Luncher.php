<?php

define('BOT_TOKEN', '245263111:AAGWnzjv9LG2HiFLKM_HgCUGymE3uw5iq_A');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
 throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
 error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}

function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    $admin = 239601689;
    $matches = explode(' ', $text);
    $substr = substr($text, 0,7 );
    if (strpos($text, "/start") === 0) {
        apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'درود دوست عزیز 😃👋

⚙ برای ساخت ربات ماشین حساب توکن ربات خود را از @botfather دریافت کرده و آن را برای ما ارسال کنید. ⚙

به عنوان مثال :
`123456789:ABCDE1FGHIJ5KLMNO5PQRS`


به ربات ما امتیاز دهید  👈        [⭐️SudoCalc⭐️](https://telegram.me/storebot?start=SudoCalc_Bot) 👉

⚠️ هر نفر = فقط یک ربات ⚠️

❤️ با تشکر ❤️
🤖 ',"parse_mode"=>"MARKDOWN","disable_web_page_preview"=>"true"));


$txxt = file_get_contents('pmembers.txt');
$pmembersid= explode("\n",$txxt);
	if (!in_array($chat_id,$pmembersid)) {
		$aaddd = file_get_contents('pmembers.txt');
		$aaddd .= $chat_id."
";
    	file_put_contents('pmembers.txt',$aaddd);
}
        if($chat_id == 239601689)
        {
          if(!file_exists('tokens.txt')){
        file_put_contents('tokens.txt',"");
           }
        $tokens = file_get_contents('tokens.txt');
        $part = explode("\n",$tokens);
       $tcount =  count($part)-1;

      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id,  "text" => "تعداد همه ربات های آنلاین :  <code>".$tcount."</code>","parse_mode"=>"HTML"));

        }
    }else if ($text == "Version") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>SudoCalc</b>
<b>ver 1.0</b>
<code>Coded By</code> @Sudo_TM
Copy Right 2016©","parse_mode"=>"html"));
    }
    else if ($matches[0] == "/update"&& strpos($matches[1], ":")) {
      
    $txtt = file_get_contents('tokenstoupdate.txt');
		$banid= explode("\n",$txtt);
		$id=$chat_id;
    if (in_array($matches[1],$banid)) {
      rmdir($chat_id);
      mkdir($id, 0700);
       file_put_contents($id.'/banlist.txt',"");
      file_put_contents($id.'/pmembers.txt',"");
      file_put_contents($id.'/msgs.txt',"درود 😃👋
پیام خود را ارسال کنید.
-!-@-#-$
🗣پیام ارسال شد");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('calc.txt');
        $phptext = str_replace("**TOKEN**",$matches[1],$phptext);
        file_put_contents($id.'/calc.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'$texttwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'/setwebhook?url=https://echo-firefoxmi.rhcloud.com/Calc/'.$chat_id.'/calc.php');
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🚀 ربات با مـوفقیت آپدیت شد ♻️"));


    }
    }
    else if ($matches[0] != "/update"&& $matches[1]==""&&$chat_id != 239601689) {
      if (strpos($text, ":")) {
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🔃 درحال برسی توکن شما 🔃"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $chat_id;
    
   $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null){
      
      if(file_exists($id)==false && in_array($chat_id,$membersid)==false){
          

        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);

     mkdir($id, 0700);
        file_put_contents($id.'/banlist.txt',"");
        file_put_contents($id.'/pmembers.txt',"");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('calc.txt');
        $phptext = str_replace("**TOKEN**",$text,$phptext);
        file_put_contents($token.$id.'/calc.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=https://echo-firefoxmi.rhcloud.com/Calc/'.$chat_id.'/calc.php');
    $unstalled = "🔰 ربات شما با موفقیت نصب شد 🔰 
⚙ برای ورود به ربات خود کلیک کنید ⚙
✌ به ربات ما امتیاز دهید ✌
 https://telegram.me/storebot?start=SudoCalc_Bot
.";
    
    $bot_url    = "https://api.telegram.org/bot245263111:AAGWnzjv9LG2HiFLKM_HgCUGymE3uw5iq_A/"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 

$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 

$output = curl_exec($ch); 
    
    
    



      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "⚠️ اخطار : شما قبلا یک ربات ثبت کرده اید ⚠️

🔰 هر نفر = فقط یک ربات 🔰

❤️ با تشکر ❤️"));
      }
    }
      
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "❌ توکن وارد شده نامعتبر می باشد ❌"));
    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "❌ توکن وارد شده نامعتبر می باشد ❌"));

}

        }else if ($matches[0] != "/update"&&$matches[1] != ""&&$matches[2] != ""&&$chat_id == 239601689) {
          
        if (strpos($text, ":")) {
          
          
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🔃 درحال برسی توکن شما 🔃"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $matches[1].$matches[2];
    
    $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null ){
        
      if(file_exists($id)==false && in_array($id,$membersid)==false){

        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);

     mkdir($id, 0700);
        file_put_contents($id.'/banlist.txt',"");
        file_put_contents($id.'/pmembers.txt',"");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('calc.txt');
        $phptext = str_replace("**TOKEN**",$matches[0],$phptext);
        file_put_contents($token.$id.'/calc.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=https://echo-firefoxmi.rhcloud.com/Calc/'.$id.'/calc.php');
    $unstalled = "🔰 ربات شما با موفقیت نصب شد 🔰 
⚙ برای ورود به ربات خود کلیک کنید ⚙
✌ به ربات ما امتیاز دهید ✌
 https://telegram.me/storebot?start=SudoCalc_Bot
.";
    
    $bot_url    = "https://api.telegram.org/bot245263111:AAGWnzjv9LG2HiFLKM_HgCUGymE3uw5iq_A/"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 

$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 

$output = curl_exec($ch); 
  
      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "⚠️ اخطار : شما قبلا یک ربات ثبت کرده اید ⚠️

🔰 هر نفر = فقط یک ربات 🔰

❤️ با تشکر ❤️"));
      }

    }
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "❌ توکن وارد شده نامعتبر می باشد ❌"));

    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "❌ توکن وارد شده نامعتبر می باشد ❌"));

}

        } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => '❌ دستور نامعتبر ❌ 
🌀 برای راهنمایی /start را بزنید 🌀
.'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => '❌ دستور نامعتبر ❌ 
🌀 برای راهنمایی /start را بزنید 🌀
.'));
  }
}


define('WEBHOOK_URL', 'https://my-site.example.com/secret-path-for-webhooks/');

if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}


