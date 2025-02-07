<?php 
	include('functions.php');

	$id_account = intval($argv[1]);
	if($id_account < 1){ exit; }
	
	global $db;
	if (!file_exists('madeline.php')){
			copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
	}
	define('MADELINE_BRANCH', '');
	include 'madeline.php';
	
	$path = dirname(dirname(__FILE__));
	$token = get_setting_account('bot_token',$id_account);
	$api_bot_url = "https://api.telegram.org/bot".$token;
	
	$status = get_setting_account('status',$id_account);
	if($status == 1){ echo "stoppend\n";exit; }

	$num_grouped = 0;
	$singleMedia = array();
	$grouped_old = 0;
	$message_list = array();
	include('async_bot.php');

?>