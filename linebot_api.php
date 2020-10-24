<?php

$channelAccessToken = 'ZpU95vyYwQ6PqhoWwDnCveMwDDGSomaQfC7XmlsodVWvkZnjZCtaiC8PcvtEoupnV9FRPDmCKiMsmFhHZ0BLOd7w13boJmUn1NLflRyQ7P656e71jvBvJxjAxxIo2w98/zvjJy8nZCYFWu6Q20cA6wdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น

$request = file_get_contents('php://input');   // Get request content

$request_json = json_decode($request, true);   // Decode JSON request

foreach ($request_json['events'] as $event)
{
	if ($event['type'] == 'message') 
	{
		if($event['message']['type'] == 'text')
		{
			$text = $event['message']['text'];
			
			$reply_message = 'ฉันได้รับข้อความ "'. $text . '" ของคุณแล้ว!';
			
			$txts = explode(" ", $text);
			
			if($text == "@บอท สวัสดี"){
					$reply_message = "สวัสดี ยินดีให้บริการ";
				}
			if($text == "@บอท แจ้งเครื่องเสีย"){
					$reply_message = "สวัสดี รับทราบ กรุณารอสักครู่ ประสานทีมงานพื่อดำเนินการและแจ้ง JOB NO.";
				}
			
		} else {
			$reply_message = 'ฉันได้รับ ' . $event['message']['type'] . ' ของคุณแล้ว!';
		}
		
	} else {
		$reply_message = 'ฉันได้รับ Event ' . $event['type'] . ' ของคุณแล้ว!';
	}
	
	if($reply_message == null || $reply_message == ""){ $reply_message =  'ขออภัยฉันไม่สามารถตอบกลับข้อความ "'. $text . '" ของคุณ!'; }
		
	// reply message
	$post_header = array('Content-Type: application/json', 'Authorization: Bearer ' . $channelAccessToken);	
	$data = ['replyToken' => $event['replyToken'], 'messages' => [['type' => 'text', 'text' => $reply_message]]];	
	$post_body = json_encode($data);	
	//$send_result = replyMessage('https://api.line.me/v2/bot/message/reply', $post_header, $post_body);
	$send_result = send_reply_message('https://api.line.me/v2/bot/message/reply', $post_header, $post_body);	
}

function mySQL_selectAll($url)
{
	$result = file_get_contents($url);
	
	$result_json = json_decode($result, true); //var_dump($result_json);
	
	$data = "ผลลัพธ์:\r\n";
		
	foreach($result_json as $values) {
		$data .= $values["user_stuid"] . " " . $values["user_firstname"] . " " . $values["user_lastname"] . "\r\n";
	}
	
	return $data;
}

function mySQL_selectFTP($url)
{
	$result = file_get_contents($url);
	
	$result_json = json_decode($result, true); //var_dump($result_json);
	
	$data = "ผลลัพธ์:\r\n";
		
	foreach($result_json as $values) {
		$data .= $values["user_password"] . "\r\n";
	}
	
	return "รหัส FTP ของคุณคือ ".$data;
}


function replyMessage($url, $post_header, $post_body)
{
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $post_header,
                'content' => $post_body,
            ],
        ]);
	
	$result = file_get_contents($url, false, $context);
	
	return $result;
}

function send_reply_message($url, $post_header, $post_body)
{
	$ch = curl_init($url);	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	$result = curl_exec($ch);
	
	curl_close($ch);
	
	return $result;
}

?>
