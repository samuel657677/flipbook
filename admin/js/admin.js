jQuery(document).ready(function ($) {
	$('.head-wrap .hndle, .handlediv').parents('.postbox').addClass('closed');
	$('.head-wrap .hndle, .handlediv').click(function(e){
		e.preventDefault();
		$(this).parent().next().toggle().parent().toggleClass('closed');
	}).parent().next().hide();

	// Toggle all
	$('.toggle-all').click(function(e){
		$('.meta-box-sortables .head-wrap').each(function(){
			$(this).next().toggle();
			$(this).parent().toggleClass('closed');
		});
		e.preventDefault();
	})

	// Show/Hide the slider_type specific options
	$('input[name="franz_settings\\[slider_type\\]"]').change(function () {
		$('[class*="row_slider_type"]').hide();
		$('.row_slider_type_' + $(this).val()).fadeIn();
	});

	// Show/Hide home page panes specific options
	$('input[name="franz_settings\\[show_post_type\\]"]').change(function () {
		$('[id*="row_show_post_type"]').hide();
		$('#row_show_post_type_' + $(this).val()).fadeIn();
		if ($(this).val() == 'cat-latest-posts') {
			$('#row_show_post_type_latest-posts').fadeIn();
		}
	});

	// To hide/show complete section
	$('input[data-toggleOptions]').change(function () {
		var target = $(this).attr('rel');
		if (target)
			$('.' + target).fadeToggle();
		else
			$(this).closest('table').next().fadeToggle();
	});

	// To Show/Hide the widget hooks
	$('a.toggle-widget-hooks').click(function () {
		$(this).closest('li').find('li.widget-hooks').fadeToggle();
		return false;
	});

	// Select all
	$('.select-all').click(function () {
		franzSelectText($(this).prop('rel'));
		return false;
	});

	// Display spinning wheel when options form is submitted
	$('.left-wrap input[type="submit"]').click(function () {
		ajaxload = '<i class="ajaxload fa fa-spinner fa-spin"></i>';
		if ($(this).parents('.panel-wrap').length > 0)
			$(this).parent().prepend(ajaxload);
		else
			$(this).parent().append(ajaxload);
	});
	$('<img/>')[0].src = franz_uri + '/images/ajax-loader.gif';

	// Save options via AJAX
	$('#franz-options-form').submit(function () {

		var data = $(this).serialize();
		data = data.replace('action=update', 'action=franz_ajax_update');

		$.post(ajaxurl, data, function (response) {
			$('.ajaxload').remove();
			franz_show_message(response);

			if (response.search('error') == -1) t = 1000
			else t = 4000;
			t = setTimeout('franz_fade_message()', t);
		});

		return false;
	});

	/* Improve <select> elements */
	if (franzAdminScript.is_rtl == false) {
		$('.chzn-select').each(function () {
			var chosenOptions = new Object();
			chosenOptions.disable_search_threshold = 10;
			chosenOptions.allow_single_deselect = true;
			chosenOptions.no_results_text = franzAdminScript.chosen_no_search_result;
			if ($(this).attr('multiple')) chosenOptions.width = '100%';
			else chosenOptions.width = '250px';

			$(this).chosen(chosenOptions);
		});
	}


	// Remember the opened options panes
	$('.head-wrap .hndle, .handlediv, .toggle-all').click(function(e) {
		e.preventDefault();
		var postboxes = $('.left-wrap .postbox');
		var openboxes = new Array();
		$('.left-wrap .panel-wrap:visible').each(function (index) {
			var openbox = $(this).parents('.postbox');
			openboxes.push(postboxes.index(openbox));
		});
		franzSetCookie('franz-tab-' + franz_tab + '-boxes', openboxes.join(','), 100);
	});

	// reopen the previous options panes
	var oldopenboxes = franzGetCookie('franz-tab-' + franz_tab + '-boxes');
	if (oldopenboxes != null && oldopenboxes != '') {
		var boxindexes = oldopenboxes.split(',');
		for (var boxindex in boxindexes) {
			$('.left-wrap .postbox:eq(' + boxindexes[boxindex] + ')').removeClass('closed').children('.panel-wrap').show();
		}
	}


	// To support the Media Uploader in the theme options
	var customUploader, uploaderTarget;
    $(document).on('click', '.media-upload', function(e) {
        e.preventDefault();
 
        // Extend the wp.media object
		var uploaderOpts = {
			title	: 'Choose Image',
			library	: { type: 'image' },
            button	: { text: 'Choose Image' },
            multiple: false
		};
		if ( $(this).data('title') ) uploaderOpts.title = $(this).data('title');
		if ( $(this).data('button') ) uploaderOpts.button.text = $(this).data('button');
		if ( $(this).data('multiple') ) uploaderOpts.multiple = $(this).data('multiple');
        customUploader = wp.media.frames.file_frame = wp.media(uploaderOpts);
 
		fieldName = $(this).data('field');
		uploaderTarget = '#' + fieldName;
		
        customUploader.on('select', function() {
			attachment = customUploader.state().get('selection').first().toJSON();
			
			if ( fieldName.indexOf('brand_icon') === 0 ) {
				if (window.franzBrandIconIndex == undefined) window.franzBrandIconIndex = $(uploaderTarget).data('count') + 1;
				else window.franzBrandIconIndex += 1;
				
				$(uploaderTarget).val(attachment.id);
				$('.left', $(uploaderTarget).parent()).html('<img src="' + attachment.url + '" alt="' + attachment.alt + '" width="' + attachment.width + '" height="' + attachment.height + '" />');
				$(uploaderTarget).parent().append('<span class="delete"><a href="#">' + franzAdminScript.delete + '</a></span>');
				$('#brand_icons').append('\
					<li class="clearfix">\
						<div class="left"><span class="image-placeholder"></span></div>\
						<input type="hidden" name="franz_settings[brand_icons][' + window.franzBrandIconIndex + '][image_id]" value="" id="brand_icon_' + window.franzBrandIconIndex + '" />\
						<label for="brand_icon_link_' + window.franzBrandIconIndex + '">' + franzAdminScript.link + '</label>\
						<input id="brand_icon_link_' + window.franzBrandIconIndex + '" type="text" name="franz_settings[brand_icons][' + window.franzBrandIconIndex + '][link]" value="" class="code" placeholder="' + franzAdminScript.optional + '" size="60" />\
						<a data-field="brand_icon_' + window.franzBrandIconIndex + '" data-title="' + uploaderOpts.title + '" data-button="' + uploaderOpts.button.text + '" href="#" class="media-upload button">' + uploaderOpts.button.text + '</a>\
					</li>\
				');
			} else {
				$(uploaderTarget).val(attachment.url);
			}				
        });
 
        //Open the uploader dialog
        customUploader.open();
    });


	/* For options in the General tab */
	if (franz_tab == 'general') {
		
		/* Mentions bar options */
		$('#brand_icons').sortable({opacity: .8});
		$(document).on('click', '#brand_icons .delete a', function(){
			$(this).parents('li').remove();
			return false;
		});
		

		/* Social profile links options */
		$('#social-profile-sortable').sortable({
			items: '.social-profile-table',
			placeholder: 'social-profile-dragging',
			opacity: .8
		});

		function delete_socialprofile() {
			$(this).closest('table').remove();
			return false;
		}
		$('.socialprofile-del').bind('click', delete_socialprofile);
		$('#new-socialprofile-type').change(function () {
			if ($('#new-socialprofile-type').val() != 'custom') {
				$('#new-socialprofile-iconurl').closest('tr').hide(); // the the custom icon url input
				$('#new-socialprofile-title').val($('#new-socialprofile-type option').filter(":selected").text()); // prefill the title for the user                        
			} else {
				$('#new-socialprofile-iconurl').closest('tr').show();
				$('#new-socialprofile-faicon').closest('tr').show();
			}
			if ($('#new-socialprofile-type').val() != 'rss') {
				$('#new-socialprofile-url-description').hide();
			} else {
				$('#new-socialprofile-url-description').show();
			}
		});
		$('#new-socialprofile-add').click(function () {
			var spData = $('#new-socialprofile-data').data();
			var $spType = $('#new-socialprofile-type');
			var $spName = $('#new-socialprofile-type option').filter(":selected").html();
			var $spTitle = $('#new-socialprofile-title');
			var $spUrl = $('#new-socialprofile-url');
			var $spIconUrl = $('#new-socialprofile-iconurl');
			var $spFaIcon = $('#new-socialprofile-faicon');
			if ($spType.val() == '-') {
				$spType.focus();
			} else if (!$spTitle.val()) {
				$spTitle.focus();
			} else if ($spType.val() != 'rss' && !$spUrl.val()) {
				$spUrl.focus();
			} else if ($spType.val() == 'custom' && ! $spIconUrl.val() && ! $spFaIcon.val() ) {
				$spFaIcon.focus();
			} else {
				var ix = $('#socialprofile-next-index').val();

				var i18n_title = $spName;
				var rowspan = 2;
				if ($spType.val() != 'custom')
					var icon = '<i class="fa fa-' + $spType.val() + '"></i>';
				else {
					if ( $spFaIcon.val() ) var icon = '<i class="fa fa-' + $spFaIcon.val() + '"></i>';
					else var icon = '<img class="mysocial-icon" src="' + $spIconUrl.val() + '" alt="" />';
				}
					
				var extraCustom = '';
				if ($spType.val() == 'rss') {
					extraCustom = '<br /><span class="description">' + $('#new-socialprofile-url-description').text() + '</span>';
				} else if ($spType.val() == spData.customTitle) {
					rowspan = 4;
					// icon = '<img class="mysocial-icon" src="' + $spIconUrl.val() + ' " />';
					extraCustom = '\
						<tr>\
								<th class="small-row">' + spData.textFaIcon + '</th>\
								<td><input type="text" name="franz_settings[social_profiles][' + ix + '][icon_fa]" value="' + $spFaIcon.val() + '" class="widefat code" /></td>\
						</tr>\
						<tr>\
								<th class="small-row">' + spData.textIconUrl + '</th>\
								<td><input type="text" name="franz_settings[social_profiles][' + ix + '][icon_url]" value="' + $spIconUrl.val() + '" class="widefat code" /></td>\
						</tr>';
				}

				$('#social-profile-sortable').append(
					'<table class="form-table social-profile-table">\
											<tr>\
													<th scope="row" rowspan="' + rowspan + '" class="small-row social-profile-title">\
															' + i18n_title + '<br />\
															<input type="hidden" name="franz_settings[social_profiles][' + ix + '][type]" value="' + $spType.val() + '" />\
															<input type="hidden" name="franz_settings[social_profiles][' + ix + '][name]" value="' + $spName + '" />\
															' + icon + '\
															<br /><span class="delete"><a href="#" class="socialprofile-del">' + spData.textDelete + '</a></span>\
													</th>\
													<th class="small-row">' + spData.textTitleAttr + '</th>\
													<td><input type="text" name="franz_settings[social_profiles][' + ix + '][title]" value="' + $spTitle.val() + '"  class="widefat code"/>\
											</tr>\
											<tr>\
													<th class="small-row">' + spData.textUrl + '</th>\
													<td><input type="text" name="franz_settings[social_profiles][' + ix + '][url]" value="' + $spUrl.val() + '" class="widefat code" /></td>\
											</tr>\
											' + extraCustom + '\
									</table>'
				);

				// reset the new form
				$('#socialprofile-next-index').val(ix + 1);
				$('option:first', $spType).attr('selected', 'selected');
				$spTitle.val('');
				$spUrl.val('');
				$spIconUrl.val('').closest('tr').hide();
				$spFaIcon.val('').closest('tr').hide();
				// rebind the del click event
				$('.socialprofile-del').unbind('click');
				$('.socialprofile-del').bind('click', delete_socialprofile);
			}
			return false;
		});
	} // end of franz_tab 'general'=


});


function hexToR(h) {
	if (h.length == 4)
		return parseInt((cutHex(h)).substring(0, 1) + (cutHex(h)).substring(0, 1), 16);
	if (h.length == 7)
		return parseInt((cutHex(h)).substring(0, 2), 16);
}

function hexToG(h) {
	if (h.length == 4)
		return parseInt((cutHex(h)).substring(1, 2) + (cutHex(h)).substring(1, 2), 16);
	if (h.length == 7)
		return parseInt((cutHex(h)).substring(2, 4), 16);
}

function hexToB(h) {
	if (h.length == 4)
		return parseInt((cutHex(h)).substring(2, 3) + (cutHex(h)).substring(2, 3), 16);
	if (h.length == 7)
		return parseInt((cutHex(h)).substring(4, 6), 16);
}

function cutHex(h) {
	return (h.charAt(0) == "#") ? h.substring(1, 7) : h
}

function franzCheckFile(f, type) {
	type = (typeof type === "undefined") ? 'options' : type;
	f = f.elements;
	if (/.*\.(txt)$/.test(f['upload'].value.toLowerCase()))
		return true;
	if (type == 'options') alert(franzAdminScript.import_select_file);
	else if (type == 'colours') alert(franzAdminScript.preset_select_file);
	f['upload'].focus();
	return false;
};

function franzSetCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
	} else var expires = "";
	document.cookie = name + "=" + value + expires + "; path=/";
}

function franzGetCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

function franzDeleteCookie(name) {
	franzSetCookie(name, "", -1);
}

function franzSelectText(element) {
	var doc = document;
	var text = doc.getElementById(element);

	if (doc.body.createTextRange) { // ms
		var range = doc.body.createTextRange();
		range.moveToElementText(text);
		range.select();
	} else if (window.getSelection) { // moz, opera, webkit
		var selection = window.getSelection();
		var range = doc.createRange();
		range.selectNodeContents(text);
		selection.removeAllRanges();
		selection.addRange(range);
	}
}

function franz_show_message(response) {
	jQuery('.franz-ajax-response').html(response).fadeIn(400);
}

function franz_fade_message() {
	jQuery('.franz-ajax-response').fadeOut(1000);
	clearTimeout(t);
}