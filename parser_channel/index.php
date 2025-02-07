<?php
	include('functions.php');
	global $db;
	if(!$session){ header('location: '.$ssl.$_SERVER['SERVER_NAME'].root_dir.'/login.php');exit; }

	$act = strip_tags($_GET['act']);
	preg_match("/^[a-zA-Z0-9\w]+/", $act,$actx);
	$act = $actx[0];

	if($act == 'logout')
	{
		foreach($_SESSION as $p=>$x)
		{
			unset($_SESSION[$p]);
		}
		
		 header('location: '.$ssl.$_SERVER['SERVER_NAME'].root_dir.'/login.php');
		 exit;
	}	

	include(dir.'head.php'); 
	$self_account_id = get_setting('self_account_id');
	$pname = get_setting_account('profile_tg',$self_account_id);

	?>
	<input type='hidden' id='page_x' value='<?=$_SERVER['REQUEST_URI'];?>'>
	<!-- <h4 style='text-align:center;color:red;font-size:19px;padding:5px;;'>Devs: @suicide_vll</h4> -->
	<ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#power"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Управление каналами</a>
    </li>
    <li class="nav-item update_channels">
      <a class="nav-link" data-toggle="tab" href="#setting_channel"><i class="fa fa-list" aria-hidden="true"></i> Настройки фильтров каналов</a>
    </li>
	    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#setting"><i class="fa fa-cogs" aria-hidden="true"></i> Общие настройки</a>
    </li>
  </ul>
	
	 <div class='col-md-12'>

		  <div class="tab-content">
			<div id="power" class="tab-pane active" style='padding:0;'>
			  <div class='col-md-12' style='padding:0;'>
			  <div class='col-md-4' style='margin-bottom:5px;padding:0;'>	
					<label><b>Канал источник:</b></label>					
					<select class='select2 list_channel_origin' style='width:100%;'>
					<option value=''></option>
					<?
						$rx = mysqli_query($db,"SELECT `channel_url`,`name` FROM `join_exists` WHERE `type` = '1' AND `name` != '' 
																									AND `profile_tg` = '$pname'");
						$num1 = mysqli_num_rows($rx);
						if($num1 == 0){ $view_o = 'display'; } else { $view_o = 'none'; }
						while($list = mysqli_fetch_assoc($rx))
						{
					?>
						<option value='<?=$list['channel_url'];?>'>🟢 <?=html_entity_decode($list['name']);?></option>
						<?}?>
					<option value='0'>Указать другой канал<option>
					</select>
					
					<input type="text" class="form-control form-control-sm" id='channel_origin' style='margin-top:2px;display:<?=$view_o;?>;' placeholder='https://t.me/joinchat/XXXXXXXXX' title='https://t.me/channel или https://t.me/joinchat/XXXXXXXXX'>
				</div>	
			  
				<div class='col-md-5'>	
					<label><b>Канал публикации:</b></label>
					<select class='select2 list_channel_publish' style='width:100%;'>
					<option value=''></option>
					<?
						$rx = mysqli_query($db,"SELECT `channel_url`,`name` FROM `join_exists` WHERE `type` = '2' AND `name` != ''
																									AND `profile_tg` = '$pname'");
						$num1 = mysqli_num_rows($rx);
						if($num1 == 0){ $view_p = 'display'; } else { $view_p = 'none'; }
						while($list = mysqli_fetch_assoc($rx))
						{
					?>
						<option value='<?=$list['channel_url'];?>'>🟢 <?=html_entity_decode($list['name']);?></option>
						<?}?>
					<option value='0'>Указать другой канал<option>
					</select>
					
					<input type="text" class="form-control form-control-sm" id='channel_publish' style='margin-top:2px;display:<?=$view_p;?>;' placeholder='https://t.me/joinchat/XXXXXXXXX'>
				</div>	
		
				<div class='col-md-2'>	
					
					<br><button class='btn btn-success add_channel'><i class="fa fa-plus" aria-hidden="true"></i> Добавить</button>
					<img src='<?=root_dir;?>/img/load.gif' id='load' style='display:none;'>
				</div>	
				<?
					$r = mysqli_query($db,"SELECT `id`,`channel_origin`,`channel_publish`,`status`,`all_post`,`publish_name`,
											`origin_name`,`setting_id` FROM `channels_origin` WHERE `account_id` = '$self_account_id'");
					$all = mysqli_num_rows($r);
				?>
				<br><label><b>Список задач: <?=$all;?></b></label>
				<table class="table table-bordered table-striped table_x1">
				  <thead>
					<tr>
					  <th scope="col" class='opt_col' style='width:36%;'>Канал публикации</th>
					  <th scope="col" class='opt_col invisible_' style='width:36%;'>Источник</th>
					  <th scope="col" class='opt_col '>Всего постов</th>
					  <th scope="col" class='opt_col invisible_'>Дейсвие</th>
					  <th scope="col" class='opt_col' style='width:41px;'>#</th>
					</tr>
				  </thead>
				  <tbody id='channels'>
				  <?
					
					while($list = mysqli_fetch_assoc($r))
					{
						if($list['status'] == 0){ $btn1 = ''; $btn2 = "style='display:none;'"; } 
												else{$btn1 = "style='display:none;'"; $btn2 = ''; }					
				  ?>
					<tr class='item<?=$list['id'];?>'>
						<td class='invisible_'>
						<select class='select2 change_channel_publish' style='width:100%;' object_id='<?=$list['id'];?>'>
						<option value='0'>-</option>
						<?							
							$rx1 = mysqli_query($db,"SELECT `id`,`name`,`channel_url` FROM `join_exists` WHERE `type` = '2' 
																							AND `profile_tg` = '$pname'");
					
							while($list_p = mysqli_fetch_assoc($rx1))
							{
								if($list_p['channel_url'] == $list['channel_publish']){ $sel = 'selected'; } else { $sel = ''; }
								
								$name_ch = unicode_char($list_p['name']);
								if(mb_strlen($name_ch) > 350){ $name_ch = mb_substr($name_ch,0,350); }
						?>
							<option value='<?=$list_p['id'];?>'  <?=$sel;?>><?=$name_ch?></option>
						<?}?>
						</select>
						
						</td>
						<td>
						<select class='select2 change_channel_origin' style='width:100%;' object_id='<?=$list['id'];?>'>
						<option value='0'>-</option>
						<?
							$rx2 = mysqli_query($db,"SELECT `id`,`name`,`channel_url` FROM `join_exists` WHERE `type` = '1' 
																							AND `profile_tg` = '$pname'");
							while($list_o = mysqli_fetch_assoc($rx2))
							{
								if($list_o['channel_url'] == $list['channel_origin']){ $sel = 'selected'; } else { $sel = ''; }
								
								$name_ch = unicode_char($list_o['name']); 
								if(mb_strlen($name_ch) > 350){ $name_ch = mb_substr($name_ch,0,350); }
						?>
							<option value='<?=$list_o['id'];?>' <?=$sel;?>><?=$name_ch?></option>
						<?}?>
						</select>
						
						</td>
						<td class='invisible_ center'><?=$list['all_post'];?></td>
						<td class='center'>
						<button class="btn btn-primary btn_posted stop stop<?=$list['id'];?>" <?=$btn1;?> value='<?=$list['id'];?>'><i class="fa fa-stop" aria-hidden="true"  ></i> Стоп</button>
							<button class="btn btn-primary btn_posted start start<?=$list['id'];?>" <?=$btn2;?> value='<?=$list['id'];?>'><i class="fa fa-play" aria-hidden="true" ></i> Старт</button>
						
						</td>
						<td class='center'><i class="fa fa-trash del_channel" aria-hidden="true" value='<?=$list['id'];?>' setting_id='<?=$list['setting_id'];?>'></i></td>
					</tr>
					<?}?>
				  </tbody>
				  </table>
				
			  </div>
			</div>
			
	<div id="setting" class="container tab-pane fade" >
		<div class='col-md-12' style='padding:0;margin-top:-22px;'>	
		
		<div class="col-md-11" style="padding:2px 0 16px 15px">
			<label><b>Текущий аккаунт:</b></label>
			<select class='select2' id='self_account' style='width:100%;margin-bottom:6px;'>
			<?
				$r = mysqli_query($db,"SELECT `id`,`profile_tg` FROM `accounts_setting`");
				while($list = mysqli_fetch_assoc($r))
				{
					if($list['id'] == $self_account_id){ $sel = 'selected'; } else { $sel = ''; }
					?><option value='<?=$list['id'];?>' <?=$sel;?>><?=$list['profile_tg'];?></option><?						
			?>
			
				<?}?>
			</select>
			
			<button type="button" class="btn btn-primary btn-xs change_account"><i class="fa fa-sign-out" aria-hidden="true"></i> Переключить</button>
			<button type="button" class="btn btn-danger btn-xs del_account" style='float:right;'><i class="fa fa-remove" aria-hidden="true"></i> Удалить</button>
			
			
		</div>
		
		
			 <div class='col-md-5'>
			 
			 <?	
				$rx = mysqli_query($db,"SELECT `bot_token`,`status` FROM `accounts_setting` WHERE `id` = '$self_account_id'");
				
				$data_x = mysqli_fetch_assoc($rx);
				$bot_token = $data_x['bot_token'];
				$status = $data_x['status'];
			 ?>
			 <div class="form-group">
				<label><b>Статус работы:</b></label>
				
				<div class="form-check-inline">
				<div class="custom-control custom-checkbox col-sm-12">
				  <input type="radio" class="custom-control-input status_bot" name="status_bot" id='status_bot1' value='0' <?if($status == 0){ echo 'checked'; }?>>
				  <label for='status_bot1' class="custom-control-label">Вкл</label>
				</div>
			
				</div>
				<div class="form-check-inline">
				<div class="custom-control custom-checkbox col-sm-12">
				  <input type="radio" class="custom-control-input status_bot" name="status_bot" id='status_bot2' value='1' <?if($status == 1){ echo 'checked'; }?>>
				  <label for='status_bot2' class="custom-control-label">Выкл</label>
				</div>
				</div>
				
				<hr>
				<h6>Переименовать Канал:</h6>
				<label><b>Выберите канал(сменится в панели и telegram):</b></label>
				<select class='select2' id='rename_channel' style='width:100%;margin-bottom:6px;'>
				<option value='0'></option>
				<?
					$r = mysqli_query($db,"SELECT `channel_id`,`name` FROM `join_exists` WHERE `type` = '2' AND `profile_tg` = '$pname'");
					while($list = mysqli_fetch_assoc($r))
					{
						?><option value='<?=$list['channel_id'];?>' ><?=$list['name'];?></option><?						
				?>
				
					<?}?>
				</select>
				<span id='new_name' style='display:none;'>
					<br><label><b>Новое название Канала:</b></label>
					<input type="text" class="form-control form-control-sm" id='channel_name'>
					
					<button type="button" class="btn btn-primary btn-xs change_name_channel"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Сменить</button>
				</span><br>
				
				<br><label><b>Bot token:</b></label>
				<input type="text" class="form-control form-control-sm" id='bot_token' value='<?=$bot_token;?>'>
				
				<hr>
				<button class='btn btn-primary save_setting' style='width:100%;'><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</button><p>
			</div>	
			</div>
			 <div class='col-md-5'>
					<label><b>Смена пароля:</b></label>
					<input type="password" class="form-control form-control-sm" id='new_pwd' >
					<button type="button" class="btn btn-primary btn-xs change_pwd"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Сменить</button>
					<a href='<?=root_dir;?>/logout/' class='btn btn-primary btn-xs'><i class="fa fa-sign-out" aria-hidden="true"></i> Завершить сессию</a>
			  </div>
			
		
	  </div>
			  
			</div>
	
	<div id="setting_channel" class="container tab-pane fade" >
		<div class='col-md-12' style='padding:0;'>	
		
		<?
			$r1 = mysqli_query($db,"SELECT `setting_id` FROM `channels_origin` WHERE `account_id` = '$self_account_id' ORDER BY `id` LIMIT 0,1");
			$data_s = mysqli_fetch_assoc($r1);
			$setting_id_1 = $data_s['setting_id'];

			$rx = mysqli_query($db,"SELECT `spam_filter`,`replace_words`,`filter_message`,`filter_inline`,`enable_text_message`,`my_text_message`,
									`replace_link`,`skip_text`,`enable_bot_vote`,`filter_links`,`timetable`,`time_post`,`limit_post`,`limit_hours`,
									`limit_time`,`limit_status`,`forward_message`,`ignore_post_type`,`sugn_channel`,`word_send_post`,
									`word_send_post_func`,`word_send_post_type`,`replace_username_stat`,`replace_username` 
																				FROM `channels_settings` WHERE `id` = '$setting_id_1'");
					
			$data_x = mysqli_fetch_assoc($rx);
			$enable_bot_vote = $data_x['enable_bot_vote'];
			$spam_filter = $data_x['spam_filter'];
			$replace_words = $data_x['replace_words'];
			$filter_message = $data_x['filter_message'];
			$filter_inline = $data_x['filter_inline'];
			$enable_text_message = $data_x['enable_text_message'];
			$my_text_message = $data_x['my_text_message'];
			$replace_link = $data_x['replace_link'];
			$skip_text = $data_x['skip_text'];
			$filter_links = $data_x['filter_links'];
			$timetable = $data_x['timetable'];
			$limit_status = $data_x['limit_status'];
			$forward_message = $data_x['forward_message'];
			$ignore_post_type = $data_x['ignore_post_type'];
			$sugn_channel = $data_x['sugn_channel'];
			$word_send_post = $data_x['word_send_post'];
			$word_send_post_func = $data_x['word_send_post_func'];
			$word_send_post_type = $data_x['word_send_post_type'];
			$replace_username_stat = $data_x['replace_username_stat'];
			$replace_username = $data_x['replace_username'];
			$sel = 'selected';
			if($timetable == 1){ $timetable_box = 'block;'; } else { $timetable_box = 'none;'; }
			if($limit_status == 1){ $limit_box = 'block;'; } else { $limit_box = 'none;'; }
			if($word_send_post_func == 1){ $word_send_x = 'block;'; } else { $word_send_x = 'none;'; }
		?>
		
		<div class='col-md-11' style='padding:2px 0 16px 15px'>
			<label><i class="fa fa-bars" aria-hidden="true"></i> <b>Выберите нужный канал для настройки фильтров:</b></label>
			<img src='<?=root_dir;?>/img/load.gif' id='load_setting' style='display:none;'>
			<select id='channel_setting' class='select2 channel_setting' style='width:98.6%;'>
			<?
				$r = mysqli_query($db,"SELECT `setting_id`,`origin_name`,`publish_name` FROM `channels_origin` WHERE `account_id` = '$self_account_id'");
				while($list = mysqli_fetch_assoc($r))
				{
			?>
				<option value='<?=$list['setting_id']?>'>Источник: <?=$list['origin_name']. " | Канал публикации: ".$list['publish_name'];?></option>
				<?}?>
			</select>
			
		</div>
		
		 <div class='col-md-5 load_data'>
			<label><b>Фильтр(стоп слова):</b></label>
			<textarea id='spam_filter' class='form-control' placeholder='Укажите стоп-слова'><?=$spam_filter;?></textarea>
			
			<label><b>Удалять слова(слово|замена):</b></label>

			<textarea id='replace_words' class='form-control' placeholder='Укажите слова которые нужно удалять\заменять'><?=$replace_words;?></textarea>
				
			<label><b>Удалять из поста указанные ссылки:</b></label>
			<?
				if(($filter_message == 4) or ($filter_message == 5)){ $dis_links = 'disabled';$link_bg = 'style="background:#f1f1f1"'; } 
				else { $dis_links = '';$link_bg = 'style="background:##f9f9f9"'; }
			?>
				<textarea id='filter_links' class='form-control' placeholder='Укажите ссылки которые нужно удалять' <?=$dis_links;?> <?=$link_bg;?>><?=$filter_links;?></textarea>	
			
			<label><b>Подпись канала(каждом посте снизу):</b></label>
			<textarea id='sugn_channel' class='form-control' placeholder='Укажите 🔱 текст подписи, или ссылку на ваш канал'><?=$sugn_channel;?></textarea>
			
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input word_send_post_func" id='word_send_post_func' <?if($word_send_post_func == 1){ echo 'checked'; }?>>
				  <label for='word_send_post_func' class="custom-control-label"><b>Публиковать только при наличии одного из слов</b></label>
				</div>
			</div>
			
			<div id='box_word_send' style='display:<?=$word_send_x;?>'>
				<select id='word_send_post_type' class='select2' style='width:100%;'>
					<option value='0' <?if($word_send_post_type == 0){ echo $sel; }?>>С учетом фильтров</option>
					<option value='1' <?if($word_send_post_type == 1){ echo $sel; }?>>В обход фильтров</option>
				</select><p>
				
				<textarea id='word_send_post' class='form-control' style='margin-top:11px;' title='При наличии указанных слов, пост будет опубликован' ><?=$word_send_post;?></textarea>
			</div>
			
		  </div>
			 
			 <div class='col-md-5 load_data' style='padding:0;'>
			 <label><b>Фильтрация контента</b></label>
			<select id='filter_message' class='select2' style='width:100%;'>
				<option value='0' <?if($filter_message == 0){ echo $sel; }?>>Не определен</option>
				<option value='1' <?if($filter_message == 1){ echo $sel; }?>>Удалять все ссылки в сообщении</option>
				<option value='2' <?if($filter_message == 2){ echo $sel; }?>>Удалять ссылки только t.me/username* , t.me/joinchat/XXXX* и @Username*</option>
				<option value='3' <?if($filter_message == 3){ echo $sel; }?>>Игнорировать при наличии ссылок c t.me/name* и t.me/joinchat/XXXX*</option>
				<option value='4' <?if($filter_message == 4){ echo $sel; }?>>Игнорировать наличие любых ссылок</option>
				<option value='5' <?if($filter_message == 5){ echo $sel; }?>>Заменять все ссылки в сообщении на свою(указать)</option>
			</select>
			
			
			<label><b>Доп фильтрация</b></label>
			<select id='ignore_post_type' class='select2' style='width:100%;'>
				<option value='0' <?if($ignore_post_type == 0){ echo $sel; }?>>Не определен</option>
				<option value='1' <?if($ignore_post_type == 1){ echo $sel; }?>>Игнорировать посты без текста(только для медиа)</option>
				<option value='2' <?if($ignore_post_type == 2){ echo $sel; }?>>Игнорировать все посты с текстом</option>
				<option value='3' <?if($ignore_post_type == 3){ echo $sel; }?>>Игнорировать посты с текстом(без медиа)</option>
				<option value='4' <?if($ignore_post_type == 4){ echo $sel; }?>>Игнорировать посты с текстом(только для медиа)</option>
				<option value='5' <?if($ignore_post_type == 5){ echo $sel; }?>>Игнорировать все посты без текста</option>
			</select>
			
			<div class='replace_link_box' <?if($filter_message <> 5){ echo 'style="display:none;"';} ?>>
			<?
				if(($filter_message == 5) && ($enable_text_message == 1)){ $dis = 'disabled';  } else { $dis = ''; }
			?>
				<label><b>Заменять на ссылку:</b></label>
				<input type="text" class="form-control form-control-sm" id='replace_link' value='<?=$replace_link;?>' <?=$dis;?>>
			</div>
			<hr>
			
			<div class='chk_inline'>
			<label><b>Функции для постов:</b></label><br>
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input" id='filter_inline' <?if($filter_inline == 1){ echo 'checked'; }?>>
				  <label for='filter_inline' class="custom-control-label"><b>Не пропускать посты с ссылками в inline в кнопках(реклама)</b></label>
				</div>
			</div>
			
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input skip_text" id='skip_text' <?if($skip_text == 1){ echo 'checked'; }?>>
				  <label for='skip_text' class="custom-control-label"><b>Игнорировать посты без медиа вложений</b></label>
				</div>
			</div>
			
			</div>
			
			<hr>
			<div class='chk_inline'>
			<label><b>Доп функции:</b></label><br>
			
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input enable_text_message" id='enable_text_message' <?if($enable_text_message == 1){ echo 'checked'; }?>>
				  <label for='enable_text_message' class="custom-control-label"><b>Заменять любой текст c канала источника на свой</b></label>
				</div>
			</div>
			<textarea id='my_text_message' class='form-control' <?if($enable_text_message == 0){ echo 'style="display:none;"'; }?> ><?=$my_text_message;?></textarea>
			
			
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input replace_username_stat" id='replace_username_stat' <?if($replace_username_stat == 1){ echo 'checked'; }?>>
				  <label for='replace_username_stat' class="custom-control-label"><b>Заменять любые @username в тексте на свой</b></label>
				</div>
			</div>
			<input type='text' id='replace_username' class='form-control' <?if($replace_username_stat == 0){ echo 'style="display:none;"'; }?> ><?=$replace_username;?></textarea>
			
			
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input bot_vote" id='enable_bot_vote' <?if($enable_bot_vote == 1){ echo 'checked'; }?>>
				  <label for='enable_bot_vote' class="custom-control-label"><b>Кнопки голосования(для фото)</b></label>
				</div>
			</div>
			
			<div class="alert alert-success bot_vote_info" role="alert" <?if($enable_bot_vote == 0){ echo 'style="display:none;"'; }?>>
			<?
				$bot_username = get_setting_account('bot_username',$self_account_id);
				if(empty($bot_username)){ $bot_username = '<span style="color:red">[Укажите token бота!]</span>'; } 
																		else { $bot_username = '@'.$bot_username; }

			?>
			 Добавте бота <b id='bot_username'><?=$bot_username;?></b> в каналы на которых будет публиковаться голосование(👍|👎).
			</div>
			
			
			
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input timetable" id='timetable' <?if($timetable == 1){ echo 'checked'; }?>>
				  <label for='timetable' class="custom-control-label"><b>По расписанию</b></label>
				</div>
			</div>
			
			<div id='time_box' style='display:<?=$timetable_box;?>'>
			<label><b>График публикации постов[<?=date('H:i');?>]:</b></label>
			<div class="row" style='margin-left:0;'>

				<?
				$time_post = explode('|',$data_x['time_post']);
				foreach($time_post as $val)
				{
					$checked_[$val] = true;
				}
				
				for($q=0;$q<25;$q++)
				{
					if($q < 10){ $q = "0".$q; }	
					if($checked_[$q]){ $sel = 'checked'; } else { $sel = ''; }
					
				?>
				
					<div class="custom-control custom-checkbox col-sm-2">
					  <input type="checkbox" class="custom-control-input time_post hour<?=$q;?>" id='chk<?=$q;?>' value='<?=$q;?>' <?=$sel;?>>
					  <label for='chk<?=$q;?>' class="custom-control-label"><?=$q;?>:00</label>
					</div>
				<?}?>
				</div>
			</div>
			
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input limit_status" id='limit_status' <?if($limit_status == 1){ echo 'checked'; }?>>
				  <label for='limit_status' class="custom-control-label"><b>Лимит постов за указанный период</b></label>
				</div>
			</div>
			
			<div id='limit_box' style='display:<?=$limit_box;?>'>
				<div class='col-md-4'>
					<label>Максимум постов:</label>
					<input type='number' class='form-control limit_count_post' value='<?=$data_x['limit_post'];?>' min='1' style='width:102px;'>
				</div>	
				
				<div class='col-md-4'>
					<label>За часов:</label>
					<input type='number' class='form-control limit_hours' value='<?=$data_x['limit_hours'];?>' min='1' style='width:102px;'>
				</div>
			</div>
			
			<div class="form-check-inline">
				<div class="custom-control custom-checkbox">
				  <input type="checkbox" class="custom-control-input forward_message" id='forward_message' <?if($forward_message == 1){ echo 'checked'; }?> <?if($enable_bot_vote == 1){ echo 'disabled'; }?>>
				  <label for='forward_message' class="custom-control-label"><b>ForwardMessage</b></label>
				</div>
			</div>
			
			</div>
			<hr>
			
			<button class='btn btn-primary save_setting_channel' style='width:100%;'><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</button><p>
			 </div>
			 
		</div>	 
	</div>	
	
			
  </div>
  
   </div>
	</div>
</body>

</div>
</html>