<?php
	include('functions.php');
	
	$path = dirname(__FILE__);
	exec("sudo chmod -R 777 $path/accounts");
	exec("sudo chown -R www-data:www-data $path/accounts");
	chdir($path);
	
	$self = 'forward_bot.php';
	$r = mysqli_query($db,"SELECT `id`,`profile_tg` FROM `accounts_setting` WHERE `status` = '0'");
	while($list = mysqli_fetch_assoc($r))
	{
		$id_account = $list['id'];
		
		$rec = shell_exec("ps auxw | grep 'php $self $id_account' | grep -v grep");
		$rec = explode("\n",$rec);
		$rec = array_filter($rec);
		$all = count($rec);	
	
		if($all > 0){ continue; }
		exec("php $self $id_account > /dev/null &");
	}
	
?>