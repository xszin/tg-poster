<?php
	include('functions.php');
	$self = basename($_SERVER['SCRIPT_FILENAME']);
	$rec = shell_exec("ps auxw | grep $self | grep -v grep");
	$rec = explode("\n",$rec);
	$rec = array_filter($rec);
	$all = count($rec);

	$status = get_setting('status');
	if($status == 1){ exit; }

	if(($all < 3) && ($_GET['act'] == 'update'))
	{	EXIT;
		$token = get_setting('bot_token');
		$url = "https://api.telegram.org/bot".$token;
		
		$time = time();
		while(true)
		{
			$status = get_setting('status');
			if($status == 1){ exit; }
			
			$sec = time() - $time;
			$sec = round($sec);
			if($sec < 5){ continue; }
			
			$r = mysqli_query($db,"SELECT `channel_publish_id`,`channel_origin_id` FROM `channels_origin` WHERE `album_get` = '1' 
																								AND `status` = '0' LIMIT 0,1");
			$num = mysqli_num_rows($r);
			if($num == 0){ continue; }
			$data_x = mysqli_fetch_assoc($r);
			$channel_id = $data_x['channel_publish_id'];
			
			$content = array(
				'chat_id' => '-100'.$channel_id,
				'text' => "update:".$data_x['channel_origin_id'],
				'disable_notification'=>true,
			);

			$data = file_get_contents($url.'/sendmessage?'.http_build_query($content));
			$json = json_decode($data,true);
			$message_id  = $json['result']['message_id'];
			if($message_id > 0)
			{
				$qur = array('message_id'=>$message_id,
							'chat_id'=>'-100'.$channel_id,
							);
				file_get_contents($url.'/deleteMessage?'.http_build_query($qur));	
			}
			
			mysqli_query($db,"UPDATE `channels_origin` SET `album_get` = '0' WHERE `channel_publish_id` = '$channel_id'");	
			$time = time();
		}
	}

?>