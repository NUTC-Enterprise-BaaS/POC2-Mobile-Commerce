(function($) {
	$current_slide = 0;
	$currently_opened = 0;
	$(window).load(function() {
		var add_form = $('#vikslider-slides-add-form');
		var json_data_to_parse = $('#jform_params_viksliderimages').html();
		if(json_data_to_parse == '') {
			json_data_to_parse = '[]';
		}
		var tabs = JSON.parse(json_data_to_parse);
		if (tabs == null || tabs == '') {
			tabs = [];
		}
		var published_text = $('#invisible').find('.vikslider-slider-status span').first().html();
		var unpublished_text = $('#invisible').find('.vikslider-slider-status span').eq(1).html();
		$('invisible').find('.vikslider-slider-status span').remove();
		var add_form_scroll_wrap = $('#vikslider-slides-add-form').find('.vikslider-newslide-cont');
		$('#vikslider-slides-add-wrapper').find('a').click(function(e) {
			e.preventDefault();
			e.stopPropagation();

			add_form_scroll_wrap.find('.vikslider-newslide-cont-add').addClass('vikslider-newslide-open').find('div').fadeIn();
		});
		$('#vikslider-slides-add-wrapper').click(function(e) {
			e.preventDefault();
			e.stopPropagation();

			add_form_scroll_wrap.find('.vikslider-newslide-cont-add').addClass('vikslider-newslide-open').find('div').fadeIn();
		});
		var add_form_btns = add_form.find('.vikslider-slideparam-block-add a');
		add_form_btns.eq(1).click(function(e) {
			if (e) {
				e.preventDefault();
				e.stopPropagation();
			}
			add_form.find('.vikslider-slideparam-block').fadeOut();
			add_form_scroll_wrap.find('.vikslider-newslide-cont-add').removeClass('vikslider-newslide-open')
			add_form.find('.viksliderparam_add_image').val('');
			add_form.find('.viksliderparam_add_title').val('');
			add_form.find('.viksliderparam_add_caption').val('');
			add_form.find('.viksliderparam_add_readmore').val('');
			add_form.find('.viksliderparam_add_published').val('1');
		});
		add_form_btns.eq(0).click(function(e) {
			create_item('new');
		});
		function create_item(source) {
			var item = $('#invisible').find('.vikslider-slider-entry').clone();
			var title = (source == 'new') ? add_form.find('.viksliderparam_add_title').val() : source.title;
			var caption = (source == 'new') ? add_form.find('.viksliderparam_add_caption').val() : source.caption || '';
			var readmore = (source == 'new') ? add_form.find('.viksliderparam_add_readmore').val() : source.readmore;
			var image = (source == 'new') ? add_form.find('.viksliderparam_add_image').val() : source.image;
			var published = (source == 'new') ? add_form.find('.viksliderparam_add_published').val() : source.published;
			item.find('.vikslider-slider-title').html(title);
			item.find('.vikslider-slider-status').attr('class', (published == 1) ? 'vikslider-slider-status published' : 'vikslider-slider-status unpublished');
			item.find('.vikslider-slider-status').attr('title', (published == 1) ? published_text : unpublished_text);
			item.find('.viksliderparam_edit_title').val(title);
			item.find('.viksliderparam_edit_caption').val(caption);
			item.find('.viksliderparam_edit_readmore').val(readmore);
			item.find('.viksliderparam_edit_image').val(image);
			item.find('.viksliderparam_edit_published').val(published);
			item.find('.vikslider-slider-editbtn').click(function(e) {
				if (e) {
					e.preventDefault();
					e.stopPropagation();
				}
				item.find('.vikslider-slider-entry-inner').trigger('click');
			});
			item.find('.vikslider-slider-entry-inner').click(function(e) {
				if (e) {
					e.preventDefault();
					e.stopPropagation();
				}
				var scroller = item.find('.vikslider-slider-editcontainer');
				scroller.css('height', scroller.outerHeight() + "px");

				if (scroller.outerHeight() > 0) {
					scroller.animate({
						height: 0
					}, 250);
				} else {
					var items = item.parent().find('.vikslider-slider-entry');
					items.each(function(i, it) {
						it = $(it);
						if (it != item)
							it.find('.vikslider-slideparam-block-edit a').eq(1).trigger('click');
					});

					scroller.animate({
						height: scroller.find('div').outerHeight() + "px"
					},	250, function() {
						if (scroller.outerHeight() > 0)
							scroller.css('height', 'auto');
						}
					);
				}
			});
			item.find('.vikslider-slider-status').click(function(e) {
				if (e) {
					e.preventDefault();
					e.stopPropagation();
				}
				var btn = item.find('.vikslider-slider-status');
				if (btn.hasClass('published')) {
					item.find('.viksliderparam_edit_published').val(0);
					btn.attr('class', 'vikslider-slider-status unpublished');
					btn.attr('title', unpublished_text);
					item.find('.vikslider-slideparam-block-edit a').eq(0).trigger('click');
				} else {
					item.find('.viksliderparam_edit_published').val(1);
					btn.attr('class', 'vikslider-slider-status published');
					btn.attr('title', published_text);
					item.find('.vikslider-slideparam-block-edit a').eq(0).trigger('click');
				}
			});
			item.find('.viksliderparam_edit_title').parent().css('display', 'block');
			item.find('.viksliderparam_edit_caption').parent().css('display', 'block');
			item.find('.viksliderparam_edit_readmore').parent().css('display', 'block');
			item.find('.viksliderparam_edit_published').parent().css('display', 'block');
			item.find('.viksliderparam_edit_image').parent().css('display', 'block');
			item.find('.vikslider-slideparam-block-edit').css('display', 'block');
			item.find('.vikslider-slider-removebtn').click(function(e) {
				if (e) {
					e.preventDefault();
					e.stopPropagation();
				}
				var items = item.parent().find('.vikslider-slider-entry');
				var item_id = items.index(item);
				tabs.splice(item_id, 1);
				item.remove();
				$('#jform_params_viksliderimages').html(JSON.encode(tabs));
			});
			item.find('.vikslider-slideparam-block-edit > a').eq(1).click(function(e) {
				if (e) {
					e.preventDefault();
					e.stopPropagation();
				}
				var scroller = item.find('.vikslider-slider-editcontainer');
				scroller.css('height', scroller.outerHeight() + "px");
				scroller.animate({height: 0}, 250);
			});
			item.find('.vikslider-slideparam-block-edit a').eq(0).click(function(e) {
				if (e) {
					e.preventDefault();
					e.stopPropagation();
				}
				var title = item.find('.viksliderparam_edit_title').val();
				var caption = item.find('.viksliderparam_edit_caption').val();
				var readmore = item.find('.viksliderparam_edit_readmore').val();
				var image = item.find('.viksliderparam_edit_image').val();
				var published = item.find('.viksliderparam_edit_published').val();
				var items = item.parent().find('.vikslider-slider-entry');
				var item_id = items.index(item);
				tabs[item_id] = {"title": htmlspecialchars(title),"caption": htmlspecialchars(caption),"readmore": readmore,"image": image,"published": published};
				item.find('.vikslider-slider-title').html(title);
				item.find('.vikslider-slider-status').attr('class', (published == 1) ? 'vikslider-slider-status published' : 'vikslider-slider-status unpublished');
				item.find('.vikslider-slider-status').attr('title', (published == 1) ? published_text : unpublished_text);
				item.find('.modal-img').attr('href', '../' + image);
				item.find('.vikslider-slideparam-block-edit a').eq(1).trigger('click');
				$('#jform_params_viksliderimages').html(JSON.encode(tabs));
			});
			if (source == 'new') {
				tabs.push({"title": title,"caption": caption,"readmore": readmore,"image": image,"published": published});
				add_form_btns.eq(1).trigger('click');
				$('#jform_params_viksliderimages').html(JSON.encode(tabs));
				SqueezeBox.assign(item.find('.vik-modal'), {parse: 'rel'});
			}
			item.appendTo($('#vikslider-allslides'));
			var wrap = item.parent();
			var items = wrap.find('.vikslider-slider-entry');
			item.find('.modal-img').attr('href', '../' + image);
			$current_slide++;
			item.find('.viksliderparam_edit_image').attr('id', 'jform_params_edit_img_' + $current_slide);
			item.find('.modal-media').attr('href', 'index.php?option=com_media&view=images&tmpl=component&asset=com_modules&author=ale&fieldid=jform_params_edit_img_' + $current_slide + '&folder=');
			item.find('.modal-media-clear').attr('onclick', 'javascript:document.getElementById(\'jform_params_edit_img_' + $current_slide + '\').val()=\'\';return false;');
		}
		tabs.each(function(tab) {
			create_item(tab);
		});

		(function() {
			SqueezeBox.assign('.vik-modal', {parse: 'rel'});
			SqueezeBox.assign('.modal', {parse: 'rel'});
		}).delay(1500);

	});
	function htmlspecialchars(string) {
		string = string.toString();
		string = string.replace(/&/g, '[ampersand]').replace(/</g, '[leftbracket]').replace(/>/g, '[rightbracket]');
		return string;
	}
	function htmlspecialchars_decode(string) {
		string = string.toString();
		string = string.replace(/\[ampersand\]/g, '&').replace(/\[leftbracket\]/g, '<').replace(/\[rightbracket\]/g, '>');
		return string;
	}
})(jQuery);