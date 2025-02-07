$(document).ready(function(){
	
	$('.select2').select2({
	  placeholder: 'Выберите',
	  allowClear: true
	});
	
    var table = $('.table_x').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": false,
	  "order": [[ 0, "desc" ]],
      "autoWidth": false
    });	
	
   var dir_x = $('#page_x').val();

	$('.save_setting').on('click',function(){
		var status_bot = $('.status_bot:checked').val();
		var profile_tg = $('#profile_tg').val();
		var bot_token = $('#bot_token').val();
		var self_account = $('#self_account').val();
		
		var data_ = new Object();
			data_['act'] = 'save_setting';
			data_['status'] = status_bot;
			data_['profile_tg'] = profile_tg;
			data_['bot_token'] = bot_token;
			data_['self_account'] = self_account;
			
		$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('.save_setting').prop('disabled',false);
							$('.box_power').css('opacity','1');
							$('#bot_username').html(card.username);
						}
				  }
			});	
	});

	$('.save_setting_channel').on('click',function(){
		var id = parseInt($('#channel_setting').val());
		
		var spam_filter = $('#spam_filter').val();
		var replace_words = $('#replace_words').val();
		var filter_message = $('#filter_message').val();
		var filter_links = $('#filter_links').val();
		var my_message = $('#my_text_message').val();
		var enable_my_message = $('#enable_text_message').prop('checked');
		var filter_inline = $('#filter_inline').prop('checked');
		var replace_link = $('#replace_link').val();
		var enable_bot_vote = $('#enable_bot_vote').prop('checked');
		var skip_text = $('#skip_text').prop('checked');
		var timetable = $('#timetable').prop('checked');
		var limit_status = $('#limit_status').prop('checked');
		var limit_count_post = parseInt($('.limit_count_post').val());
		var limit_hours = parseInt($('.limit_hours').val());
		var forward_message = $('#forward_message').prop('checked');
		var ignore_post_type  = $('#ignore_post_type').val();
		var sugn_channel = $('#sugn_channel').val();
		var word_send_post_type = $('#word_send_post_type').val();
		var word_send_post_func = $('#word_send_post_func').prop('checked');
		var word_send_post = $('#word_send_post').val();
		var replace_username = $('#replace_username').val();
		var replace_username_stat = $('#replace_username_stat').prop('checked');
		
		$('.save_setting_channel').prop('disabled',true);
		$('.box_power').css('opacity','0.5');
		
		if(enable_my_message == true){ enable_my_message = 1; filter_message = 5; } else { enable_my_message = 0; }
		if(filter_inline == true){ filter_inline = 1; } else { filter_inline = 0; }
		if(enable_bot_vote == true){ enable_bot_vote = 1; } else { enable_bot_vote = 0; }
		if(skip_text == true){ skip_text = 1; } else { skip_text = 0; }
		if(timetable == true){ timetable = 1; } else { timetable = 0; }
		if(limit_status == true){ limit_status = 1; } else { limit_status = 0; }
		if(forward_message == true){ forward_message = 1; } else { forward_message = 0; }
		if(word_send_post_func){ word_send_post_func = 1; } else { word_send_post_func = 0; }
		if(replace_username_stat == true){ replace_username_stat = 1; } else { replace_username_stat = 0; }
		
		var time_post = '';
		var q = 0;
		$('.time_post').each(function(){
			var sel = $(this).prop('checked');
			
			if(sel)
			{
				var time = $(this).attr('value');
				if(q == 0){ time_post = time; q++; } else { time_post = time_post+'|'+time; }
			}
		});
		
		var data_ = new Object();
			data_['act'] = 'save_setting_channel';
			data_['id'] = id;
			data_['spam_filter'] = spam_filter;
			data_['replace_words'] = replace_words;
			data_['filter_message'] = filter_message;
			data_['my_message'] = my_message;
			data_['enable_my_message'] = enable_my_message;
			data_['filter_inline'] = filter_inline;
			data_['replace_link'] = replace_link;
			data_['filter_links'] = filter_links;
			data_['enable_bot_vote'] = enable_bot_vote;
			data_['skip_text'] = skip_text;
			data_['time_post'] = time_post;
			data_['timetable'] = timetable;
			data_['limit_status'] = limit_status;
			//data_['limit_post'] = limit_post;
			data_['limit_count_post'] = limit_count_post;
			data_['limit_hours'] = limit_hours;
			data_['forward_message'] = forward_message;
			data_['ignore_post_type'] = ignore_post_type;
			data_['sugn_channel'] = sugn_channel;
			data_['word_send_post_type'] = word_send_post_type;
			data_['word_send_post_func'] = word_send_post_func;
			data_['word_send_post'] = word_send_post;
			data_['replace_username'] = replace_username;
			data_['replace_username_stat'] = replace_username_stat;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('.save_setting_channel').prop('disabled',false);
							$('.box_power').css('opacity','1');
						}
				  }
			});
		
	});
	

	
	$('.add_channel').on('click',function(){
		
		var channel_origin = $('.list_channel_origin').val();
		if(channel_origin == 0){ var channel_origin = $('#channel_origin').val(); }
				
		var channel_publish = $('.list_channel_publish').val();
		if(channel_publish == 0){ var channel_publish = $('#channel_publish').val(); }
	
		if((channel_origin == '') | (channel_publish == '') ){ return false; }
		
		$('.add_channel').prop('disabled',true);
		$('#load').show();
		var data_ = new Object();
			data_['act'] = 'add_channel';
			data_['channel_origin'] = channel_origin;
			data_['channel_publish'] = channel_publish;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							var tr = '<tr class="item'+card.id+'"><td>'+card.opt_p+'</td><td>'+card.opt_o+'</td><td class="invisible_ center">0</td><td class="invisible_ center"><button class="btn btn-primary btn_posted stop stop'+card.id+'" value="'+card.id+'"><i class="fa fa-stop" aria-hidden="true"></i> Стоп</button><button class="btn btn-primary btn_posted start start'+card.id+'" value="'+card.id+'" style="display:none;"><i class="fa fa-play" ></i> Старт</button></td><td class="invisible_ center"><i class="fa fa-trash del_channel" value="'+card.id+'" setting_id="'+card.setting_id+'"></i></td><tr>'
							$('#channels').append(tr);
							$('#load').hide();
							$('.add_channel').prop('disabled',false);
							$('#channel_origin').val('');
							$('#channel_publish').val('');
							if(card.id > 0)
							{
								var option_x = new Option("Источник: "+card.name_o+" | Канал публикации: "+card.name_s, card.setting_id, false, false);
								$('#channel_setting').append(option_x).trigger('change');
							}
						}
						if(card.opt_p_upate !== '')
						{ 
							$('.list_channel_publish').html(card.opt_p_upate);
							$('.list_channel_origin').html(card.opt_o_upate);
							$('#rename_channel').html(card.opt_p_mv);
						}
						if(card.stat == 2){ alert('Ошибка:'+card.error);$('#load').hide();$('.add_channel').prop('disabled',false); }
						
						$('.select2').select2({
						  placeholder: 'Выберите',
						  allowClear: true
						});
				  }
			});
		
	});
	
	$('body').on('click','.del_channel',function(){

			
		var id = parseInt($(this).attr('value'));
		var setting_id = parseInt($(this).attr('setting_id'));
		
		var data_ = new Object();
			data_['act'] = 'del_channel';
			data_['id'] = id;
			data_['setting_id'] = setting_id;
		
	
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('.item'+id).remove();
							$('#channel_setting').html(card.opt);
						}
				  }
			});
	});
	
	$('body').on('click','.start',function(){
		var id = parseInt($(this).attr('value'));
		
		var data_ = new Object();
			data_['act'] = 'set_status_channel';
			data_['status'] = 0;
			data_['id'] = id;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('.start'+id).hide();
							$('.stop'+id).show();
						}
				  }
			});
	});
	
	$('body').on('click','.stop',function(){
		var id = parseInt($(this).attr('value'));

		var data_ = new Object();
			data_['act'] = 'set_status_channel';
			data_['status'] = 1;
			data_['id'] = id;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('.stop'+id).hide();
							$('.start'+id).show();
						}
				  }
			});
	});



	$('body').on('click','.change_pwd',function(){
		var pwd = $('#new_pwd').val();
		if(pwd == ''){ return false; }
		
		var data_ = new Object();
			data_['act'] = 'change_pwd';
			data_['pwd'] = pwd;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							window.location.href = dir_x;
						}
				  }
			});
	});
	
	
	$('.enable_text_message').on('click',function(){
		if($(this).prop('checked') == true)
		{ 
			$('#my_text_message').show();
			$('#filter_message option[value="5"]').prop('disabled',true);
			$('#replace_link').prop('disabled',true);
			$('#filter_links').prop('disabled',true);
			$('#filter_links').css('background','#f1f1f1');
			$('#replace_username_stat').prop('disabled',true);
			$('#replace_username').prop('disabled',true);
		} else 
		{ 
			$('#my_text_message').hide();
			$('#filter_message option[value="5"]').prop('disabled',false); 
			$('#replace_link').prop('disabled',false);
			$('#filter_links').prop('disabled',false);
			$('#filter_links').css('background','#f9f9f9');
			$('#replace_username_stat').prop('disabled',false);
			$('#replace_username').prop('disabled',false);
		}
		
	});
	
	$('#filter_message').on('change',function(){
		var type = parseInt($(this).val());
		
		$('#filter_links').prop('disabled',false);
		$('#filter_links').css('background','#f9f9f9');
		$('.replace_link_box').hide();

		if((type == 4) | (type == 1))
		{ 
			$('#filter_links').prop('disabled',true);
			$('#filter_links').css('background','#f1f1f1');
			
		}
		
		if(type == 5)
		{ 
			$('.replace_link_box').show(); 
			$('#filter_links').prop('disabled',true);
			$('#filter_links').css('background','#f1f1f1');
		}
		
	});

	$('#enable_bot_vote').on('click',function(){
		if($(this).prop('checked') == true)
		{ 
			$('.bot_vote_info').show(); 
			$('#forward_message').prop('disabled',true);
		} else { $('.bot_vote_info').hide();$('#forward_message').prop('disabled',false); }
	});

	$('#channel_setting').on('change',function(){
		var id = parseInt($(this).val());
		$('.load_data').css('opacity','0.5');
		$('#load_setting').show();
		
		var data_ = new Object();
			data_['act'] = 'get_setting_channel';
			data_['id'] = id;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('#spam_filter').val(card.spam_filter);
							$('#filter_links').val(card.filter_links);
							$('#my_text_message').val(card.my_text_message);
							$('#replace_link').val(card.replace_link);
							$('#replace_words').val(card.replace_words);
							$('#sugn_channel').val(card.sugn_channel);
							$('#word_send_post').val(card.word_send_post);
							$('#word_send_post_type').val(card.word_send_post_type).change();
							
							
							if(card.word_send_post_func == 1)
							{ 
								$('#box_word_send').show();
								$('#word_send_post_func').prop('checked',true);
							} else { $('#box_word_send').hide();$('#word_send_post_func').prop('checked',false); }
							
							
							if(card.enable_bot_vote == 1)
							{ 
								$('.bot_vote_info').show();$('.bot_vote').prop('checked',true); 
								$('#forward_message').prop('disabled',true);
							}else 
							{ 
								$('.bot_vote_info').hide();
								$('.bot_vote').prop('checked',false);
								$('#forward_message').prop('disabled',false);
							}
							
							if(card.replace_username_stat == 1)
							{
								$('#replace_username').show();
								$('#replace_username').val(card.replace_username);
								$('#replace_username_stat').prop('checked',true);
							}else
							{
								$('#replace_username').hide();
								$('#replace_username').val('');
								$('#replace_username_stat').prop('checked',false);
							}
							
							if(card.filter_inline == 1){ $('#filter_inline').prop('checked',true);} else{ $('#filter_inline').prop('checked',false)}
							if(card.skip_text == 1){ $('.skip_text').prop('checked',true); } else{ $('.skip_text').prop('checked',false);}
							if(card.enable_text_message == 1){ $('.enable_text_message').prop('checked',true); }
														else { $('.enable_text_message').prop('checked',false); }
							if(card.forward_message == 1){ $('#forward_message').prop('checked',true); }
															else { $('#forward_message').prop('checked',false); }					
							$('#filter_message').val(card.filter_message).change();
							
							if(card.enable_text_message == 1)
							{ 
								$('#my_text_message').show();
								$('#filter_message option[value="5"]').prop('disabled',true);
								$('#replace_link').prop('disabled',true);
								
								$('#replace_username_stat').prop('disabled',true);
								$('#replace_username').prop('disabled',true);
							} else 
							{ 
								$('#my_text_message').hide();
								$('#filter_message option[value="5"]').prop('disabled',false); 
								$('#replace_link').prop('disabled',false);
								
								$('#replace_username_stat').prop('disabled',false);
								$('#replace_username').prop('disabled',false);
							}
							
							var timetable = false;
							if(card.timetable == 1){ $('#time_box').show(); timetable = true; } else { $('#time_box').hide(); }
							$('.time_post').prop('checked',false);
							$('.timetable').prop('checked',timetable);
							for(q=0;q<card.time_post.length;q++)
							{
								$('.hour'+card.time_post[q]).prop('checked',true);
							}
							
							limit_status_x = false;
							if(card.limit_status == 1){ $('#limit_box').show(); limit_status_x = true; } else{ $('#limit_box').hide(); }
							$('.limit_count_post').val(card.limit_post);
							$('.limit_hours').val(card.limit_hours);
							$('#limit_status').prop('checked',limit_status_x);
							
							
							$('#ignore_post_type').val(card.ignore_post_type).change();
							
							$('.load_data').css('opacity','1');
							$('#load_setting').hide();
							disabled_filt(card.word_send_post_type);
						}
				  }
			});
	});
	
	function disabled_filt(type)
	{
		var status = false;
		if(type == 1){ status = true; }
		
		$('#spam_filter').prop('disabled',status);
		$('#replace_words').prop('disabled',status);
		$('#filter_links').prop('disabled',status);
		$('#filter_message').prop('disabled',status);
		$('#ignore_post_type').prop('disabled',status);
		$('#skip_text').prop('disabled',status);
		$('#filter_inline').prop('disabled',status);
		$('#enable_text_message').prop('disabled',status);	
	}
	
	$('#word_send_post_type').on('change',function(){
		var type = $(this).val();
		disabled_filt(type);
	});
	
	$('.timetable').on('change',function(){
		if($(this).prop('checked')){ $('#time_box').show();} else { $('#time_box').hide(); }
	});
	
	$('.limit_status').on('change',function(){
		if($(this).prop('checked')){ $('#limit_box').show();} else { $('#limit_box').hide(); }
	});
	
	$('.word_send_post_func').on('click',function(){
		if($(this).prop('checked'))
		{ 
			$('#box_word_send').show(); 
			var type = parseInt($('#word_send_post_type').val());
			disabled_filt(type);
		} else { $('#box_word_send').hide();disabled_filt(0); }		
	});
	
	$('.list_channel_origin').on('change',function(){
		var x = parseInt($(this).val());
		if(x == 0){ $('#channel_origin').show(); }else { $('#channel_origin').hide(); }
	});


	$('.list_channel_publish').on('change',function(){
		var x = parseInt($(this).val());
		if(x == 0){ $('#channel_publish').show(); }else { $('#channel_publish').hide(); }
	});
	
	$('body').on('change','.change_channel_publish',function(){
		var id = parseInt($(this).val());
		var object_id = $(this).attr('object_id');
		
		var data_ = new Object();
			data_['act'] = 'set_publish_channel';
			data_['id'] = id;
			data_['object_id'] = object_id;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						
				  }
			});
	});
	
	
	$('body').on('change','.change_channel_origin',function(){
		var id = parseInt($(this).val());
		var object_id = $(this).attr('object_id');
		
		var data_ = new Object();
			data_['act'] = 'set_origin_channel';
			data_['id'] = id;
			data_['object_id'] = object_id;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						
				  }
			});
	});
	
	
	
	$('.replace_username_stat').on('click',function(){
		if($(this).prop('checked') == true)
		{ 
			$('#replace_username').show();
		} else 
		{ 
			$('#replace_username').hide();
		}
		
	});
	
	
	$('body').on('click','.change_account',function(){
		var id = parseInt($('#self_account').val());
		if(isNaN(id)){ return false; }
		
		var data_ = new Object();
			data_['act'] = 'set_current_account';
			data_['id'] = id;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							window.location.href = dir_x;
						}
				  }
			});
	});
	
	$('body').on('click','.del_account',function(){
		var id = parseInt($('#self_account').val());
		if(isNaN(id)){ return false; }
		
		var data_ = new Object();
			data_['act'] = 'del_account';
			data_['id'] = id;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('#self_account option[value="'+id+'"]').remove();
						}
				  }
			});
	});
	
	
	$('body').on('change','#rename_channel',function(){
		var id = parseInt($(this).val());
		if(id < 1){ $('#new_name').hide(); } 
		else
		{ 
			$('#new_name').show(); 
			$('#channel_name').val($('#rename_channel option:selected').text());
		}
		
	});	
	
	
	$('body').on('click','.change_name_channel',function(){
		var id = parseInt($('#rename_channel option:selected').val());
		var name = $('#channel_name').val();
		
		if((id < 1) || (name == '')){ return false; }
		$('.change_name_channel').prop('disabled',true);
		
		var data_ = new Object();
			data_['act'] = 'change_name_channel';
			data_['id'] = id;
			data_['name'] = name;
		
			$.ajax({
				  type: "POST",
				  url: dir_x+'ajax.php',
				  data: data_,
				  dataType:"text",
				  success: function(data)
				  { 
						var card = JSON.parse(data);
						if(card.stat == 1)
						{
							$('.change_name_channel').prop('disabled',false);
						}
						
						if(card.stat == 0){ alert('Error:'+card.error); } 
				  }
			});
	});
	
});