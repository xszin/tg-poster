<?php
	include('functions.php');
	set_time_limit(0); 
	error_reporting(0);
	ini_set('display_errors', 0);
	
	if (!file_exists('madeline.php')){
			copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
	}
	include 'madeline.php';
	$path = dirname(__FILE__);
	
	$device_model_list = file($path.'/files/device_model.txt');
	$device_model = $device_model_list[array_rand($device_model_list)];
	
	$settings['authorization']['default_temp_auth_key_expires_in'] = 86400 * 30 * 12;
	$settings['app_info']['api_id'] = 16282;
	$settings['app_info']['api_hash'] = 'ebfaa22710b9372b4679b8e81d5bf264';
	$settings['logger']['logger'] = 0;
	$settings['app_info']['device_model'] = $device_model;
	$settings['app_info']['system_version'] = rand(1,15).'.0';
	$settings['app_info']['app_version'] ='Android '.rand(1,15).'.0';
	$settings['app_info']['lang_code'] = 'eng';
	$settings['app_info']['lang_pack'] = '';
	
	$login = readline('Enter your phone number:');
	$login = str_replace(' ','',$login);
	$profile = $login.'.tg';
	
	try
	{ 
		$MadelineProto = new \danog\MadelineProto\API($path.'/accounts/'.$profile,$settings);
		$MadelineProto->phone_login($login); 
		$authorization = $MadelineProto->completePhoneLogin(readline('Enter the code you received: ')); 
		if ($authorization['_'] === 'account.noPassword') {
			throw new \danog\MadelineProto\Exception('2FA is enabled but no password is set!');
		}
		if ($authorization['_'] === 'account.password') {
			$authorization = $MadelineProto->complete2falogin(readline('Please enter your password (hint '.$authorization['hint'].'): ')); 
		}
		if ($authorization['_'] === 'account.needSignup') {
			$authorization = $MadelineProto->completeSignup(readline('Please enter your first name: '), readline('Please enter your last name (can be empty): '));
		}
		$MadelineProto->session = $path.'/accounts/'.$profile;
		
		mysqli_query($db,"INSERT INTO `accounts_setting` (`profile_tg`,`status`) VALUES ('$profile','0')");
		$id_account = mysqli_insert_id($db);
	
		$dialogs = $MadelineProto->messages->getDialogs([ 'offset_date' => 0, 'offset_id' => 0, 'limit' => 0,  ]);
		if(isset($dialogs['chats'][0]['_']))
		{
			$dialogs_group = array();
			foreach($dialogs['chats'] as $item)
			{
				if($item['_'] === 'channel')
				{
					$channel_origin_id = $item['id'];
					if(isset($item['username'])){ $channel_origin = 'https://t.me/'.$item['username']; } else { continue; }
					if(isset($item['title'])){ $name = $item['title'];$origin_name = unicode_html($item['title']); } 
																				else {$origin_name = '';$name = '';}
					
					mysqli_query($db,"INSERT INTO `join_exists` (`channel_url`,`profile_tg`,`type`,`name`,`channel_id`,`id_account`) 
										VALUES ('$channel_origin','$profile','1','$origin_name','$channel_origin_id','$id_account')");
					echo "IMPORT: $name:$channel_origin\n";
				}
			}
		}
		unset($MadelineProto);
		exec("sudo chmod -R 777 $path/accounts");
		exec("sudo chown -R www-data:www-data $path/accounts");
		echo "Profile:$profile - Created!\n";
	}catch(\danog\MadelineProto\RPCErrorException $e) {  echo $error = $e->getMessage()."\n";exit; }
	
?>