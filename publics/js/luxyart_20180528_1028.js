$(function(){
	//ヘッダー
	$('#g-nav-login-btn').on('mouseenter mouseleave',function(){
		$(this).toggleClass('open');
		$('.g-nav__login').toggleClass('open');
	});
	$('.g-nav__login').disableAutoFill();
	$('#user_email_popup').on('change','input',function(){
		$('#g-nav-login-btn').toggleClass('open');	
		$('.g-nav__login').toggleClass('open');
	});
	$('#user_pass_popup').on('change','input',function(){
		$('#g-nav-login-btn').toggleClass('open');		
		$('.g-nav__login').toggleClass('open');
	});
	
	$('#sp-nav__entry').on('click',function(){
		$('.sp-nav__entry-menu').toggleClass('open');
	})

	//トップフォーム
	$('.top-detail-search__color').on('click','label',function(){
		var id = this.id;
		if($('#color-'+ id).is(':checked')){
  			$('#color-'+ id).attr('checked', false);
		}
		else
		{
			$('#color-'+ id).attr('checked', true);
		}
		$(this).toggleClass('label-select');
		
	});
	$('.top-detail-search__limit').on('change','input',function(){		
		$(this).parent('label').toggleClass('select');
    	$(this).attr("checked", true);
		
	});
	$('.top-mainvisual__form-type').on('click','span',function(){
		$('.top-detail-search').toggleClass('open');
	});

	//カテゴリーページ
	$('.category-tab').on('click','li:not(".current")',function(){
		var select = $(this).find('div').attr('class');
		$('.category-tab').find('li').removeClass('current')
		$(this).addClass('current');
		$('.category-img').children('div:visible').css('display','none');
		$('.category-img').children('.category-img__'+select).fadeIn(200);
	});

	//リストページ
	$('#detail-search-btn').on('click',function(){
		$('#list-search-detail').toggleClass('open');
	});
		
	$('.list-search-compe__select').on('change','select',function(){
		var text = $(this).val();
		$(this).parent('div').prev('span').text(text);
		
	})
	$('.list-search-detail__select1').on('change','select',function(){
		var text = $(".list-search-detail__select1 option:selected").text();
		$(this).parent('div').prev('span').text(text);
		
	})
	$('.list-search-detail__select2').on('change','select',function(){
		
		var text = $(".list-search-detail__select2 option:selected").text();
		$(this).parent('div').prev('span').text(text);
		
	})
	 
	$('#color-column').on('click',function(){
		$('#list-search-detail__color').toggleClass('open');
	})
	$('#list-search-detail__color').on('click','label',function(){
		var id = this.id;
		if($('#color-'+ id).is(':checked')){
  			$('#color-'+ id).attr('checked', false);
		}
		else
		{
			$('#color-'+ id).attr('checked', true);
		}
		$(this).toggleClass('label-select');
	});
	
	$('.list-img__wrapper').find('.pinto').on('mouseenter mouseleave',function(){
		$(this).find('.list-img__favorite').toggleClass('open');
	});
	/*$('.list-img__favorite').on('click',function(){
		$(this).toggleClass('set');
	});*/

  // SPメニュー
	$("#sp-nav-open").slideMenu({ 
		main_contents: "#container",
		menu: ".sp-menu",
		menu_contents: ".sp-menu__wrapper",
	});

	// 画像
	//$('.flex-images').flexImages({rowHeight: 240});

	$('#compe-check').on({
		'mouseenter' : function(){
			$(this).find('.list-img__check').addClass('list-img__check--open')
		},
		'mouseleave' : function(){
			$(this).find('.list-img__check').removeClass('list-img__check--open')
		}},
		'.item'
	);

	$('#compe-checked').on({
		'mouseenter' : function(){
			$(this).find('.list-img__delete').addClass('list-img__delete--open')
		},
		'mouseleave' : function(){
			$(this).find('.list-img__delete').removeClass('list-img__delete--open')
		}},
		'.item'
	);

	// クレジットカード
	$('.payment-select__list').on('click',function(){
		$('.payment-select').find('.payment-select__list').removeClass('choice');
		$(this).addClass('choice');
		$(this).find('input').prop("checked",true);
	});

	//素材ページ
	$('.detail__img-zoom').on('click',function(){
		$('.detail-img-big').fadeIn(200);
	});
	$('.detail-img-big__inner').on('click',function(){
		$('.detail-img-big').fadeOut(200);
	});

	//マイページ
  $('#favorite-all-select').on('click','input',function(){
  	if($(this).is(':checked')){
  		$('.mypage-img-list__list').find('input').prop("checked",true);
  	}else{
  		$('.mypage-img-list__list').find('input').prop("checked",false);
  	}
  });
  $('.message-all-select').on('click','input',function(){
  	if($(this).is(':checked')){
  		$('.message-all-select').find('input').prop("checked",true);
  		$('.mypage-message-list__list').find('input').prop("checked",true);
  	}else{
  		$('.message-all-select').find('input').prop("checked",false);
  		$('.mypage-message-list__list').find('input').prop("checked",false);
  	}
  });
  $('.alert__delete').on('click',function(){
  	$(this).parent('div').remove();
  });


});

