<?php
	header('Content-Type: text/html; charset=utf-8');
	include('functions.php');
	
	if(!$session){ exit; }
	$act = strip_tags($_POST['act']);
	$id = intval($_POST['id']);
	preg_match("/^[a-zA-Z0-9\w]+/", $act,$actx);
	$act = $actx[0];
	
	$id = intval($_POST['id']);
	
	$pwd = strip_tags($_POST['pwd']);
	if($act == 'change_pwd')
	{
		$hash = md5($pwd);
		mysqli_query($db,"UPDATE `Accounts` SET `password` = '$hash' WHERE `id` = '$user_id'");
		echo json_encode(array('stat'=>1));
	}
	
	
	$status = intval($_POST['status']);
	$profile_tg = strip_tags($_POST['profile_tg']);
	$spam_filter = strip_tags($_POST['spam_filter']);
	$replace_words = htmlspecialchars($_POST['replace_words']);
	$bot_token = strip_tags($_POST['bot_token']);
	$filter_message = intval($_POST['filter_message']);
	$my_message = htmlspecialchars($_POST['my_message']);
	$enable_my_message = intval($_POST['enable_my_message']);
	$filter_inline = intval($_POST['filter_inline']);
	$replace_link = strip_tags($_POST['replace_link']);
	$enable_bot_vote = intval($_POST['enable_bot_vote']);
	$filter_links = strip_tags($_POST['filter_links']);
	$skip_text = intval($_POST['skip_text']);
	$timetable = intval($_POST['timetable']);
	$time_post = strip_tags($_POST['time_post']);
	$limit_post = intval($_POST['limit_count_post']);
	$limit_hours = intval($_POST['limit_hours']);
	$limit_status = intval($_POST['limit_status']);
	$forward_message = intval($_POST['forward_message']);
	$ignore_post_type = intval($_POST['ignore_post_type']);
	$sugn_channel = unicode_html($_POST['sugn_channel']);
	$word_send_post_type = intval($_POST['word_send_post_type']);
	$word_send_post_func = intval($_POST['word_send_post_func']);
	$word_send_post = unicode_html($_POST['word_send_post']);
	$replace_username = strip_tags($_POST['replace_username']);
	$replace_username_stat = intval($_POST['replace_username_stat']);	
	if($act == 'save_setting_channel')
	{
		$my_message = unicode_html($my_message);

		mysqli_query($db,"UPDATE `channels_settings` SET `spam_filter` = '$spam_filter', `replace_words` = '$replace_words',
							`filter_message` = '$filter_message', `my_text_message` = '$my_message', 
							`enable_text_message` = '$enable_my_message', `filter_inline` = '$filter_inline',
							`enable_bot_vote` = '$enable_bot_vote',`replace_link` = '$replace_link',`filter_links` = '$filter_links',
							`skip_text` = '$skip_text',`time_post` = '$time_post',`timetable` = '$timetable',
							`limit_post` = '$limit_post',`limit_hours` = '$limit_hours', `limit_status` = '$limit_status',
							`forward_message` = '$forward_message',`ignore_post_type` = '$ignore_post_type', `sugn_channel` = '$sugn_channel', 
							`word_send_post_type` = '$word_send_post_type', `word_send_post_func` = '$word_send_post_func',
							`word_send_post` = '$word_send_post', `replace_username` = '$replace_username', 
							`replace_username_stat` = '$replace_username_stat' WHERE `id` = '$id'");
		
		echo json_encode(array('stat'=>1));
	}
	
	$self_account = intval($_POST['self_account']);
	if($act == 'save_setting')
	{
		if(!empty($bot_token))
		{
			$url = "https://".$_SERVER['SERVER_NAME'].root_dir."/hook_vote.php";
			$u = "https://api.telegram.org/bot$bot_token/setWebhook?max_connections=$max&url=".$url;
			file_get_contents($u);
		}
		
		$res = file_get_contents("https://api.telegram.org/bot".$bot_token."/getme");
		$json = json_decode($res);
		if($json->ok == 1)
		{
			$username = $json->result->username;
			mysqli_query($db,"UPDATE `accounts_setting` SET `bot_username` = '$username' WHERE `id` = '$self_account'");
		}
		
		$my_message = unicode_html($my_message);
		if(empty($username)){ $username = '<span style="color:red">[Укажите token бота!]</span>'; } else { $username = '@'.$username; }
	
		mysqli_query($db,"UPDATE `accounts_setting` SET `status` = '$status',  `bot_token` = '$bot_token' WHERE `id` = '$self_account'");
		echo json_encode(array('stat'=>1,'username'=>$username));
	}
	
	$channel_publish = strip_tags($_POST['channel_publish']);
	$channel_origin = strip_tags($_POST['channel_origin']);
	if($act == 'add_channel')
	{	
		$self_account_id = $_SESSION['self_account_id'];
		$profile = get_setting_account('profile_tg',$self_account_id);
		$pname = $profile;
		
		$rx1 = mysqli_query($db,"SELECT `id`,`name`,`channel_id` FROM `join_exists` WHERE `channel_url` = '$channel_origin' 
										AND `profile_tg` = '$profile' AND `id_account` = '$self_account_id' AND `type` = '1'");
		$num1 = mysqli_num_rows($rx1);
		
		$rx2 = mysqli_query($db,"SELECT `id`,`name`,`channel_id` FROM `join_exists` WHERE `channel_url` = '$channel_publish' 
											AND `profile_tg` = '$profile' AND `id_account` = '$self_account_id' AND `type` = '2'"); 
		$num2 = mysqli_num_rows($rx2);
		if(($num1 > 0) && ($num2 > 0))
		{
			$res_origin = mysqli_fetch_assoc($rx1); 
			$channel_origin_id = $res_origin['channel_id'];
			$origin_name = $res_origin['name'];
			
			$res_publish = mysqli_fetch_assoc($rx2); 
			$channel_publish_id = $res_publish['channel_id'];
			$publish_name = $res_publish['name'];
		}else
		{
		
		if (!file_exists('madeline.php')){ 
			copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
		}
		define('MADELINE_BRANCH', '');
		include 'madeline.php';

		$settings['app_info']['api_id'] = 16282;
		$settings['app_info']['api_hash'] = 'ebfaa22710b9372b4679b8e81d5bf264';
		$settings['logger']['logger'] = 0;
		
		$profile = $_SERVER['DOCUMENT_ROOT'].root_dir.'/accounts/'.$profile;
		try
		{
			$MadelineProto = new \danog\MadelineProto\API($profile,$settings);
			$MadelineProto->async(true);
			$MadelineProto->loop(function () use ($MadelineProto){
			yield $MadelineProto->start();
			global $db;
			global $channel_origin;
			global $channel_publish;
			global $channel_origin_id;
			global $origin_name;
			global $channel_publish_id;
			global $publish_name;
			global $pname;
			global $self_account_id;
	
			$channel_origin_id = 0;
			$channel_publish_id = 0;

			try
			{ 	
				$rx = mysqli_query($db,"SELECT `id`,`name`,`channel_id` FROM `join_exists` WHERE `channel_url` = '$channel_origin' 
																				AND `profile_tg` = '$pname' AND `type` = '1'");
				$num1 = mysqli_num_rows($rx);
				if($num1 == 0)
				{
					try
					{
						yield $MadelineProto->channels->joinChannel(['channel' => $channel_origin, ]); 
						
						$data_origin = yield $MadelineProto->channels->getFullChannel(['channel' => $channel_origin,]);
						$channel_origin_id = $data_origin['full_chat']['id'];
						$origin_name = unicode_html($data_origin['chats'][0]['title']);
				
						mysqli_query($db,"INSERT INTO `join_exists` (`channel_url`,`profile_tg`,`type`,`name`,`channel_id`,`id_account`) 
											VALUES ('$channel_origin','$pname','1','$origin_name','$channel_origin_id','$self_account_id')");
					}catch(\danog\MadelineProto\RPCErrorException $e) 
					{  	
						$error = $e->getMessage();
						if($error == 'INVITE_HASH_INVALID'){ echo json_encode(array('stat'=>2,'error'=>$error));exit; }
						if(strstr($error,'FLOOD_WAIT_'))
						{ 
							list($n,$sec) = explode('WAIT_',$error);
							if($sec > 60){ $sec = round($sec / 60); $sec = $sec.' мин'; } else { $sec = $sec.' sec'; }
							echo json_encode(array('stat'=>2,'error'=>"Превышен лимит вступлений, немного подождите. $sec"));
							exit; 
						}elseif($error == 'USER_ALREADY_PARTICIPANT')
						{
							$data_origin = yield $MadelineProto->channels->getFullChannel(['channel' => $channel_origin,]);
							$channel_origin_id = $data_origin['full_chat']['id'];
							$origin_name = unicode_html($data_origin['chats'][0]['title']);
					
							mysqli_query($db,"INSERT INTO `join_exists` (`channel_url`,`profile_tg`,`type`,`name`,`channel_id`,`id_account`) 
												VALUES ('$channel_origin','$pname','1','$origin_name','$channel_origin_id','$self_account_id')");
						}
					}  
				}else
				{ 
					$res_origin = mysqli_fetch_assoc($rx); 
					$channel_origin_id = $res_origin['channel_id'];
					$origin_name = $res_origin['name'];
				}
				
				$rx = mysqli_query($db,"SELECT `id`,`name`,`channel_id` FROM `join_exists` WHERE `channel_url` = '$channel_publish' 
																					AND `profile_tg` = '$pname' AND `type` = '2'"); 
				$num2 = mysqli_num_rows($rx);
				if($num2 == 0)
				{
					try
					{
						yield $MadelineProto->channels->joinChannel(['channel' => $channel_publish, ]);
						
						$data_publish = yield $MadelineProto->channels->getFullChannel(['channel' => $channel_publish,]);
						$channel_publish_id = $data_publish['full_chat']['id'];
						$publish_name = unicode_html($data_publish['chats'][0]['title']);
	  					
						mysqli_query($db,"INSERT INTO `join_exists` (`channel_url`,`profile_tg`,`type`,`name`,`channel_id`,`id_account`) 
											VALUES ('$channel_publish','$pname','2','$publish_name','$channel_publish_id','$self_account_id')");
					}catch(\danog\MadelineProto\RPCErrorException $e) 
					{  
						$error = $e->getMessage();
						if($error == 'INVITE_HASH_INVALID'){ echo json_encode(array('stat'=>2,'error'=>$error));exit; }
						if(strstr($error,'FLOOD_WAIT_'))
						{ 
							list($n,$sec) = explode('WAIT_',$error);
							if($sec > 60){ $sec = round($sec / 60); $sec = $sec.' мин'; } else { $sec = $sec.' sec'; }
							echo json_encode(array('stat'=>2,'error'=>"Превышен лимит вступлений, немного подождите. $sec")); 
							exit; 
						}elseif($error == 'USER_ALREADY_PARTICIPANT')
						{
							$data_publish = yield $MadelineProto->channels->getFullChannel(['channel' => $channel_publish,]);
							$channel_publish_id = $data_publish['full_chat']['id'];
							$publish_name = unicode_html($data_publish['chats'][0]['title']);
						
							mysqli_query($db,"INSERT INTO `join_exists` (`channel_url`,`profile_tg`,`type`,`name`,`channel_id`,`id_account`) 
											VALUES ('$channel_publish','$pname','2','$publish_name','$channel_publish_id','$self_account_id')");
						}
					} 
				}else
				{ 
					$res_publish = mysqli_fetch_assoc($rx); 
					$channel_publish_id = $res_publish['channel_id'];
					$publish_name = $res_publish['name'];
				}
				
			}catch(\danog\MadelineProto\RPCErrorException $e) 
			{  	
				$error = $e->getMessage();
				if($error == 'INVITE_HASH_INVALID'){ echo json_encode(array('stat'=>2,'error'=>$error));exit; }
				elseif(strstr($error,'FLOOD_WAIT_'))
				{ 
					list($n,$sec) = explode('WAIT_',$error);
					echo json_encode(array('stat'=>2,'error'=>"Превышен лимит, немного подождите. $sec sec"));
					exit; 
				}

			} 
			
		});
			
		}catch(\danog\MadelineProto\RPCErrorException $e) { $error = $e->getMessage(); echo json_encode(array('stat'=>2,'error'=>$error));exit; } 
		}
		
		$time = time();
		mysqli_query($db,"INSERT INTO `channels_settings` (`status`, `spam_filter`, `replace_words`, `filter_links`, `filter_message`, 	
							`enable_text_message`, `my_text_message`, `filter_inline`, `replace_link`, `enable_bot_vote`, `skip_text`,`limit_time`) 
							VALUES (0, '@\nРеклама\nРекламе\nподписаться\nканал\nФулл залили\nПиши мне\nзакреп\nУдалим через\nУдалю через\nУдаляю через\nЧас и удаляем\nв телеграм\nпромокод\nставку\n http://bit.ly\n1xbet\n1bet\ncasino\nвпустит ещё\nНаписать\nПрогноз на матч\nв лс', 'Казино\nhttps://t.me/joinchat/|#\n@intimfact|@in4bo\n#реклама', 'http://mail.ru\nclc.to\nhttp://vk.com', 0, 0, '&lt;b&gt;Kill4me&lt;/b&gt; go', 0, '', 0, 0,'$time')");
		$setting_id = mysqli_insert_id($db);
		
		mysqli_query($db,"INSERT INTO `channels_origin` (`origin_name`,`publish_name`,`channel_publish`,`channel_origin`,`channel_origin_id`
																							,`channel_publish_id`,`setting_id`,`account_id`) 
											VALUES ('$origin_name','$publish_name','$channel_publish','$channel_origin','$channel_origin_id',
																					'$channel_publish_id','$setting_id','$self_account_id')");
		$id = mysqli_insert_id($db);
		
		$opt_p = '';
		$opt_p_upate = '';
		$opt_p_mv = "<option value='0'></option>";
		$rx1 = mysqli_query($db,"SELECT `id`,`name`,`channel_url`,`channel_id` FROM `join_exists` WHERE `type` = '2' AND `profile_tg` = '$pname'");
		while($list_p = mysqli_fetch_assoc($rx1))
		{ 
			if($list_p['channel_url'] == $channel_publish){ $sel = 'selected'; } else { $sel = ''; }
			$name_ch = $list_p['name'];
			if(mb_strlen($name_ch) > 350){ $name_ch = substr($name_ch,0,350); }
			$opt_p .= "<option value='".$list_p['id']."' $sel>".$name_ch."</option>";
			$opt_p_upate .= "<option value='".$list_p['channel_url']."'>".$name_ch."</option>"; 
			$opt_p_mv .= "<option value='".$list_p['channel_id']."'>".$name_ch."</option>"; 
		}
		
		$opt_p_upate .= "<option value='0'>Указать другой канал<option>";
		$opt_o = '';
		$opt_o_upate = '';
		$rx2 = mysqli_query($db,"SELECT `id`,`name`,`channel_url` FROM `join_exists` WHERE `type` = '1' AND `profile_tg` = '$pname'");
		while($list_o = mysqli_fetch_assoc($rx2))
		{
			if($list_o['channel_url'] == $channel_origin){ $sel = 'selected'; } else { $sel = ''; }
			$name_ch = $list_o['name'];
			if(mb_strlen($name_ch) > 350){ $name_ch = mb_substr($name_ch,0,350); }
			$opt_o .= "<option value='".$list_o['id']."' $sel>".$name_ch."</option>";
			$opt_o_upate .= "<option value='".$list_o['channel_url']."'>".$name_ch."</option>"; 
		}
		
		$opt_o_upate .= "<option value='0'>Указать другой канал<option>";
		$opt_p = "<select class='select2 change_channel_publish' style='width:100%;' object_id='$id'><option value='0'>-</option>$opt_p</select>";
		$opt_o = "<select class='select2 change_channel_origin' style='width:100%;' object_id='$id'><option value='0'>-</option>$opt_o</select>";
	
		echo json_encode(array('stat'=>1,'id'=>$id,'setting_id'=>$setting_id,'channel_name'=>$publish_name,'origin_name'=>$origin_name,
												'name_o'=>html_entity_decode($origin_name),'name_s'=>html_entity_decode($publish_name),
												'opt_p'=>$opt_p,'opt_o'=>$opt_o,'opt_p_upate'=>$opt_p_upate,'opt_o_upate'=>$opt_o_upate,
												'opt_p_mv'=>$opt_p_mv));
	}
	
	$setting_id = intval($_POST['setting_id']);
	if($act == 'del_channel')
	{
		mysqli_query($db,"DELETE FROM `channels_origin` WHERE `id` = '$id'");
		mysqli_query($db,"DELETE FROM `channels_settings` WHERE `id` = '$setting_id'");

		$r = mysqli_query($db,"SELECT `setting_id`,`origin_name`,`publish_name` FROM `channels_origin`");
		$opt = '';
		while($list = mysqli_fetch_assoc($r))
		{
			$opt .= "<option value='".$list['setting_id']."'>Источник: ".$list['origin_name']. ' | Канал публикации: '.$list['publish_name']."</option>";
		}

		echo json_encode(array('stat'=>1,'opt'=>$opt));
	}
	
	$status = intval($_POST['status']);
	if($act == 'set_status_channel')
	{
		mysqli_query($db,"UPDATE `channels_origin` SET `status` = '$status' WHERE `id` = '$id'");
		echo json_encode(array('stat'=>1));
	}

	if($act == 'get_setting_channel')
	{
		$r = mysqli_query($db,"SELECT `id`,`status`, `spam_filter`, `replace_words`, `filter_links`, `filter_message`, 	`enable_text_message`, 
								`my_text_message`, `filter_inline`, `replace_link`, `enable_bot_vote`, `skip_text`, `time_post`,`timetable`, 
												`limit_post`,`limit_hours`,`limit_status`,`forward_message`,`ignore_post_type`,`sugn_channel`, 
								`word_send_post_func`,`word_send_post`,`word_send_post_type`,`replace_username`,`replace_username_stat` 
																								FROM `channels_settings` WHERE `id` = '$id'");
		$data_x = mysqli_fetch_assoc($r);
		
		$time_post = explode("|",$data_x['time_post']);
		echo json_encode(array(
						'stat'=>1,
						'status'=>intval($data_x['status']),
						'spam_filter'=>$data_x['spam_filter'],
						'replace_words'=>htmlspecialchars_decode($data_x['replace_words']),
						'filter_links'=>$data_x['filter_links'],
						'filter_message'=>intval($data_x['filter_message']),
						'enable_text_message'=>intval($data_x['enable_text_message']),
						'my_text_message'=>html_entity_decode($data_x['my_text_message']),
						'filter_inline'=>intval($data_x['filter_inline']),
						'replace_link'=>$data_x['replace_link'],
						'enable_bot_vote'=>intval($data_x['enable_bot_vote']),
						'skip_text'=>intval($data_x['skip_text']),
						'timetable'=>intval($data_x['timetable']),
						'time_post'=>$time_post,						
						'limit_status'=>intval($data_x['limit_status']),
						'limit_post'=>intval($data_x['limit_post']),
						'limit_hours'=>intval($data_x['limit_hours']),
						'ignore_post_type'=>intval($data_x['ignore_post_type']),
						'sugn_channel'=>unicode_char($data_x['sugn_channel']),
						'word_send_post_func'=>intval($data_x['word_send_post_func']),
						'word_send_post'=>unicode_char($data_x['word_send_post']),
						'word_send_post_type'=>intval($data_x['word_send_post_type']),
						'replace_username_stat'=>intval($data_x['replace_username_stat']),
						'replace_username'=>$data_x['replace_username'],
						'forward_message'=>intval($data_x['forward_message'])));
	}
	
	$object_id = intval($_POST['object_id']);
	if($act == 'set_publish_channel')
	{		
		$rx = mysqli_query($db,"SELECT `id`,`name`,`channel_url`,`channel_id` FROM `join_exists` WHERE `id` = '$id'");
		$data_x = mysqli_fetch_assoc($rx);
		$channel_publish = $data_x['channel_url'];
		$channel_id = $data_x['channel_id'];
		$publish_name = $data_x['name'];
		
		mysqli_query($db,"UPDATE `channels_origin` SET `channel_publish` = '$channel_publish', `channel_publish_id` = '$channel_id',
																		`publish_name` = '$publish_name' WHERE `id` = '$object_id'");
		echo json_encode(array('stat'=>1));
	}
	
	if($act == 'set_origin_channel')
	{		
		$rx = mysqli_query($db,"SELECT `id`,`name`,`channel_url`,`channel_id` FROM `join_exists` WHERE `id` = '$id'");
		$data_x = mysqli_fetch_assoc($rx);
		$channel_origin = $data_x['channel_url'];
		$channel_id = $data_x['channel_id'];
		$origin_name = $data_x['name'];
		
		mysqli_query($db,"UPDATE `channels_origin` SET `channel_origin` = '$channel_origin', `channel_origin_id` = '$channel_id',
																		`origin_name` = '$origin_name' WHERE `id` = '$object_id'");
		echo json_encode(array('stat'=>1));
	}
	
	if($act == 'set_current_account')
	{
		mysqli_query($db,"UPDATE `setting` SET `self_account_id` = '$id' WHERE `id` = '1'");
		echo json_encode(array('stat'=>1));
	}
	
	if($act == 'del_account')
	{
		$p = get_setting_account('profile_tg',$id);
		$profile = $_SERVER['DOCUMENT_ROOT'].root_dir.'/accounts/'.$p;
		if(file_exists($profile)){ unlink($profile);unlink($profile.'.lock'); }
		
		$r = mysqli_query($db,"SELECT `id` FROM `accounts_setting` LIMIT 0,1");
		$data_x = mysqli_fetch_assoc($r);
		$id_account = intval($data_x['id']);
		mysqli_query($db,"UPDATE `setting` SET `self_account_id` = '$id_account' WHERE `id` = '1'");
		
		mysqli_query($db,"DELETE FROM `accounts_setting` WHERE `profile_tg` = '$p'");
		mysqli_query($db,"DELETE FROM `accounts_setting` WHERE `id` = '$id'");
		mysqli_query($db,"DELETE FROM `channels_origin` WHERE `account_id` = '$id'");
		mysqli_query($db,"DELETE FROM `channels_origin` WHERE `id_account` = '$id'");
		echo json_encode(array('stat'=>1));
	}
	
	$name = strip_tags($_POST['name']);
	if($act == 'change_name_channel')
	{
		if (!file_exists('madeline.php')){ 
			copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
		}
		define('MADELINE_BRANCH', '');
		include 'madeline.php';

		$settings['app_info']['api_id'] = 16282;
		$settings['app_info']['api_hash'] = 'ebfaa22710b9372b4679b8e81d5bf264';
		$settings['logger']['logger'] = 0;
		
		$name_html = unicode_html($name);
		
		$self_account_id = $_SESSION['self_account_id'];
		$profile = get_setting_account('profile_tg',$self_account_id);
		$profile = $_SERVER['DOCUMENT_ROOT'].root_dir.'/accounts/'.$profile;
		try
		{
			$MadelineProto = new \danog\MadelineProto\API($profile,$settings);
			$MadelineProto->start();
			
			$MadelineProto->channels->editTitle(['channel' => '-100'.$id, 'title' => $name, ]);
			mysqli_query($db,"UPDATE `join_exists` SET `name` = '$name_html' WHERE `channel_id` = '$id'");
			mysqli_query($db,"UPDATE `channels_origin` SET `publish_name` = '$name_html' WHERE `channel_publish_id` = '$id'");
			
		}catch(\danog\MadelineProto\RPCErrorException $e) 
		{  	
			$error = $e->getMessage();
			echo json_encode(array('stat'=>0,'error'=>$error));
			exit;
		}
		
		echo json_encode(array('stat'=>1));
	}
	
?>