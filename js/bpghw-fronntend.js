/*
* @package bp-group-home-widgets
*/



// Setup variables for button images
jQuery(document).ready(function($){

	//Make videos Responsive
	// Find all iframes
	var $iframes = jQuery( ".bpghw-video-wrapper iframe" );

	// Find &#x26; save the aspect ratio for all iframes
	$iframes.each(function () {
	  jQuery( this ).data( "ratio", this.height / this.width )
		// Remove the hardcoded width &#x26; height attributes
		.removeAttr( "width" )
		.removeAttr( "height" );
	});

	// Resize the iframes when the window is resized
	jQuery( window ).resize( function () {
	  $iframes.each( function() {
		// Get the parent container&#x27;s width
		var width = $( this ).parent().width();
		jQuery( this ).width( width )
		  .height( width * $( this ).data( "ratio" ) );
	  });
	// Resize to fix all iframes on page load.
	}).resize();

	// fix for responsive images in text editor 
	$('#bpghw_display_text_1 img').attr('width', '100%').attr('height', '');
	$('#bpghw_display_text_2 img').attr('width', '100%').attr('height', '');

	// Set up the sortable widgets
	$( function() {
		$( "#sortable-ghw" ).sortable({
			update: function( event, ui ) {
				$(this).children().each(function (index) {
					if ($(this).attr('data-position') != (index+1)) {
						$(this).attr('data-position', (index+1)).addClass('updated');
					}
				});
				var positions = [];
				$('.updated').each(function() {
					positions.push([$(this).attr('data-name'),$(this).attr('data-position')]);
					$(this).removeClass('updated');
				});
				$.ajax({
					url : ajax_object.ajaxurl,
					type : 'post',
					data : {
						positions : positions,
						security : ajax_object.check_nonce,
						action : "bpghw_moveable_widgets"
					},
					success : function(data) {
						if ( data == 1 ) {
						
						} else {
						
						}
						
					},
					error : function(data){
						console.log(data);
					}
				});
				
			}
		});
		$( "#sortable-ghw" ).disableSelection();
	} );
	
	// Open up the edit widgets dialogue
	function openWidgets(e){

		var resetButton = document.getElementById( 'bpghw-reset-widget' );
		var widgetForm = document.getElementById( 'bpghw-widget-form' );

		var widgets = [ 'text_1', 'text_2', 'video_1', 'video_2', 'members', 'activity', 'comments', 'mention_us' ]	;
		
		var video1 = document.getElementById( 'bpghw_video_1' );
		var video1Button = document.getElementById( 'bpghw_add_video_1' );
		var video1ClearButton = document.getElementById( 'bpghw_clear_video_1' );

		var video2 = document.getElementById( 'bpghw_video_2' );
		var video2Button = document.getElementById( 'bpghw_add_video_2' );
		var video2ClearButton = document.getElementById( 'bpghw_clear_video_2' );

		var text1 = document.getElementById( 'bpghw_text_1' );
		var text1Button = document.getElementById( 'bpghw_add_text_1' );
		var text1ClearButton = document.getElementById( 'bpghw_clear_text_1' );

		var text2 = document.getElementById( 'bpghw_text_2' );
		var text2Button = document.getElementById( 'bpghw_add_text_2' );
		var text2ClearButton = document.getElementById( 'bpghw_clear_text_2' );

		var members = document.getElementById( 'bpghw_members' );
		var membersButton = document.getElementById( 'bpghw_add_members' );
		var membersClearButton = document.getElementById( 'bpghw_clear_members' );

		var activity = document.getElementById( 'bpghw_activity' );
		var activityButton = document.getElementById( 'bpghw_add_activity' );
		var activityClearButton = document.getElementById( 'bpghw_clear_activity' );

		var comments = document.getElementById( 'bpghw_comments' );
		var commentsButton = document.getElementById( 'bpghw_add_comments' );
		var commentsClearButton = document.getElementById( 'bpghw_clear_comments' );

		var mentionUs = document.getElementById( 'bpghw_mention_us' );
		var mentionUsButton = document.getElementById( 'bpghw_add_mention_us' );
		var mentionUsClearButton = document.getElementById( 'bpghw_clear_mention_us' );

		resetButton.style.display = 'block';
		this.style.display = 'none';
		
		if ( video1 != null ) {
			video1.style.display = 'block';
			video1Button.style.display = 'block';
		}
		if ( video1ClearButton != null ) {
			video1ClearButton.style.display = 'block';
		}
		if ( video2 != null ) {
			video2.style.display = 'block';
			video2Button.style.display = 'block';
		}
		if ( video2ClearButton != null ) {
			video2ClearButton.style.display = 'block';
		}
		
		if ( text1 != null ) {
			text1.style.display = 'block';
			text2.style.display = 'block';
			text1Button.style.display = 'block';
			text2Button.style.display = 'block';
		}
		if ( text1ClearButton != null ) {
			text1ClearButton.style.display = 'block';
		}
		if ( text2ClearButton != null ) {
			text2ClearButton.style.display = 'block';
		}
		
		if ( video1 != null ) {
			video1.style.display = 'block';
			video1Button.style.display = 'block';
		}
		if ( video1ClearButton != null ) {
			video1ClearButton.style.display = 'block';
		}
		if ( video2 != null ) {
			video2.style.display = 'block';
			video2Button.style.display = 'block';
		}
		if ( video2ClearButton != null ) {
			video2ClearButton.style.display = 'block';
		}

		if ( members != null ) {
			members.style.display = 'block';
			membersButton.style.display = 'block';
		}
		if ( membersClearButton != null ) {
			membersClearButton.style.display = 'block';
		}

		if ( activity != null ) {
			activity.style.display = 'block';
			activityButton.style.display = 'block';
		}
		if ( activityClearButton != null ) {
			activityClearButton.style.display = 'block';
		}

		if ( comments != null ) {
			comments.style.display = 'block';
			commentsButton.style.display = 'block';
		}
		if ( commentsClearButton != null ) {
			commentsClearButton.style.display = 'block';
		}

		if ( mentionUs != null ) {
			mentionUs.style.display = 'block';
			mentionUsButton.style.display = 'block';
		}
		if ( mentionUsClearButton != null ) {
			mentionUsClearButton.style.display = 'block';
		}

		widgetForm.style.display = 'block';
	}

	$('.bpghw-add-widget-button').off().on('click', openWidgets);
	
	// function to reset the user widget data to defaults
	function resetWidget(e){

		var clicked = e.target;
		var groupId = clicked.getAttribute( 'data-group');
		var feedback = document.getElementById( 'bpghw_info' );
		
		feedback.style.display = 'block';
		feedback.innerHTML = bpghw_translate.resetWidget;
		
		$.ajax({
			url : ajax_object.ajaxurl,
			type : 'post',
			data : {
				groupId,
				security : ajax_object.check_nonce,
				action : "bpghw_reset_widget"
			},
			success : function(data) {
				if ( data == 1 ) {
					feedback.innerHTML = bpghw_translate.successRefresh;
				} else {
					feedback.innerHTML = bpghw_translate.tryAgain;
				}
				
			},
			error : function(data){
				feedback.innerHTML = bpghw_translate.tryAgain;
			}
		});
			
	}

	$('.bpghw-reset-widget-button').off().on('click', resetWidget);

	// Generic Widget Functions
	
	// Open add/edit form
	function openInputForm(e){

		var clicked = e.target;
		var name = clicked.getAttribute( 'data-name' );
		var inputForm = document.getElementById( 'bpghw_form_' + name );
		var ClearButton = document.getElementById( 'bpghw_clear_' + name );
		var title = document.getElementById( 'bpghw_desc_' + name );

		if ( inputForm.style.display == 'none' ) {
			inputForm.style.display = 'block';
//			ClearButton.style.display = 'block'
			clicked.value = bpghw_translate.cancel;
		} else {
			inputForm.style.display = 'none';
			if ( title.innerHTML == name ) {
				clicked.value = bpghw_translate.add;
			} else { 
				clicked.value = bpghw_translate.change;
			}
		}
		

	}

	$('.bpghw_add').off().on('click', openInputForm);

	// Clear widget function
	function clearWidget(e){

		var clicked = e.target;
		var groupId = clicked.getAttribute( 'data-group');
		var name = clicked.getAttribute( 'data-name' );
		var feedback = document.getElementById( 'bpghw_feedback_' + name );
		var displayContent = document.getElementById('bpghw_display_' + name );
		var ClearButton = document.getElementById( 'bpghw_clear_' + name );
		var addButton = document.getElementById( 'bpghw_add_' + name );
		var title = document.getElementById( 'bpghw_desc_' + name );
		var inputForm = document.getElementById( 'bpghw_form_' + name );
		feedback.style.display = 'block';
		feedback.innerHTML = bpghw_translate.deleting;

		$.ajax({
			url : ajax_object.ajaxurl,
			type : 'post',
			data : {
				groupId,
				name : name,
				security : ajax_object.check_nonce,
				action : "bpghw_clear_widget"
			},
			success : function(data) {
				if ( data == 1 ) {
					feedback.innerHTML = bpghw_translate.success;
					displayContent.style.display = 'none';
					ClearButton.style.display = 'none'
					addButton.value = bpghw_translate.add;
					title.innerHTML = name;
					inputForm.style.display = 'none';
				} else {
					feedback.innerHTML = bpghw_translate.tryAgain;
				}
				
			},
			error : function(data){
				feedback.innerHTML = bpghw_translate.tryAgain;
			}
		});
			
	}

	$('.bpghw_clear_video_button').off().on('click', clearWidget);
	$('.bpghw_clear_text_button').off().on('click', clearWidget);
	$('.bpghw_clear_members_button').off().on('click', clearWidget);
	$('.bpghw_clear_activity_button').off().on('click', clearWidget);
	$('.bpghw_clear_comments_button').off().on('click', clearWidget);
	$('.bpghw_clear_mention_us_button').off().on('click', clearWidget);

	// Video Widget Functions

	//Add video URL
	function addVideoUrl(e){

		var clicked = e.target;
		var groupId = clicked.getAttribute( 'data-group');
		var name = clicked.getAttribute( 'data-name' );
		var videoForm = document.getElementById( 'bpghw_form_' + name );
		var videoInputUrl = document.getElementById( 'bpghw_url_' + name );
		var videoTitle = document.getElementById( 'bpghw_title_' + name );
		var title = document.getElementById( 'bpghw_desc_' + name );
		var displayContent = document.getElementById('bpghw_display_' + name );
		var feedback = document.getElementById( 'bpghw_feedback_' + name );
		var addButton = document.getElementById( 'bpghw_add_' + name );
		feedback.style.display = 'block';

		if ( videoInputUrl.value != '' ) {

			feedback.innerHTML = bpghw_translate.addingVideo;
			
			$.ajax({
				url : ajax_object.ajaxurl,
				type : 'post',
				data : {
					groupId,
					name,
					videoURL : videoInputUrl.value,
					title : videoTitle.value,
					security : ajax_object.check_nonce,
					action : "bpghw_add_video"
				},
				success : function(data) {
					if ( data ) {
						videoForm.style.display = 'none';
						displayContent.style.display = 'block';
						displayContent.innerHTML = data;
						feedback.innerHTML = bpghw_translate.success;
						addButton.value = bpghw_translate.change;
						title.innerHTML = videoTitle.value;
						} else {
						feedback.innerHTML = bpghw_translate.tryAgain;
					}
					
				},
				error : function(data){
					feedback.innerHTML = bpghw_translate.tryAgain;
				}
			});
			
		} else {
			
			feedback.innerHTML = bpghw_translate.enterVideo;
			
		}
	}

	$('.bpghw_submit_video').off().on('click', addVideoUrl);
	

	// Text Widget Functions
	
	//Add text input
	function addText(e){

		var clicked = e.target;
		var groupId = clicked.getAttribute( 'data-group');
		var name = clicked.getAttribute( 'data-name' );
		var textForm = document.getElementById( 'bpghw_form_' + name );
		var textContent =  tinyMCE.get('bpghw_content_' + name);
		var textTitle = document.getElementById( 'bpghw_title_' + name );
		var title = document.getElementById( 'bpghw_desc_' + name );
		var textInput = document.getElementById( 'bpghw_content_input_' + name );
		var displayContent = document.getElementById('bpghw_display_' + name );
		var feedback = document.getElementById( 'bpghw_feedback_' + name );
		var addButton = document.getElementById( 'bpghw_add_' + name );

		if ( null === textContent ) {
			textContent = document.getElementById( 'bpghw_content_' + name ).value;
		} else {
			textContent =  textContent.getContent();
		}
		
		feedback.style.display = 'block';

		if ( textContent.value != '' ) {
			feedback.innerHTML = bpghw_translate.addingText;
			$.ajax({
				url : ajax_object.ajaxurl,
				type : 'post',
				data : {
					groupId,
					name,
					content : textContent,
					title : textTitle.value,
					security : ajax_object.check_nonce,
					action : "bpghw_add_text"
				},
				success : function(data) {
					if ( data ) {
						textForm.style.display = 'none';
//						textInput.style.display = 'none';
						if ( displayContent != null ) {
							displayContent.innerHTML = data;
							addButton.value = bpghw_translate.change;
							title.innerHTML = textTitle.value;
							$('#bpghw_display_' + name + ' img').attr('width', '100%').attr('height', '');
							displayContent.style.display = 'block';
						}
						feedback.innerHTML = bpghw_translate.success;
					} else {
						feedback.innerHTML = bpghw_translate.tryAgain;
					}
					
				},
				error : function(data){
					feedback.innerHTML = bpghw_translate.tryAgain;
				}
			});
			
		} else {
			
			feedback.innerHTML = bpghw_translate.enterText;
			
		}
	}

	$('.bpghw_submit_text').off().on('click', addText);

	//Add follow and BuddyPress input
	function addWidget(e){

		var clicked = e.target;
		var groupId = clicked.getAttribute( 'data-group');
		var name = clicked.getAttribute( 'data-name' );
		var widgetForm = document.getElementById( 'bpghw_form_' + name );
		var widgetUsers =  document.getElementById( 'bpghw_max_users_' + name );
		var widgetImgSize =  document.getElementById( 'bpghw_img_size_' + name );
		var widgetTitle = document.getElementById( 'bpghw_title_' + name );
		var title = document.getElementById( 'bpghw_desc_' + name );
		var displayContent = document.getElementById('bpghw_display_' + name );
		var feedback = document.getElementById( 'bpghw_feedback_' + name );
		var addButton = document.getElementById( 'bpghw_add_' + name );
		if ( widgetUsers != null ) {
			if ( isNaN( widgetUsers.value ) ) {
				max = 8;
			} else {
				max = widgetUsers.value;
			}
			imgSize = 150;
		} else {
			imgSize = 150;
			max = 10;
		}
		feedback.style.display = 'block';
		switch( name ) {
			
			case 'members' :
				feedback.innerHTML = bpghw_translate.addingMembers;
				break;
			case 'activity' :
				feedback.innerHTML = bpghw_translate.addingActivity;
				break;
			case 'comments' :
				feedback.innerHTML = bpghw_translate.addingComments;
				break;
			case 'mention_us' :
				var mentionUsEnable = document.getElementById('bpghw_mention_us_enable');
				if ( mentionUsEnable.value == 'display' ) {
					feedback.innerHTML = bpghw_translate.addingMentionUs;
				} else {
					return;
				}
				break;
		}
		$.ajax({
			url : ajax_object.ajaxurl,
			type : 'post',
			data : {
				groupId,
				name,
				max,
				imgSize,
				title : widgetTitle.value,
				security : ajax_object.check_nonce,
				action : "bpghw_add_widget"
			},
			success : function(data) {
				if ( data ) {
					widgetForm.style.display = 'none';
					if ( displayContent != null ) {
						displayContent.innerHTML = data;
						addButton.value = bpghw_translate.change;
						title.innerHTML = widgetTitle.value;
						displayContent.style.display = 'block';
					}
					feedback.innerHTML = bpghw_translate.success;
				} else {
					feedback.innerHTML = bpghw_translate.tryAgain;
				}
				
			},
			error : function(data){
				feedback.innerHTML = bpghw_translate.tryAgain;
			}
		});
		
	}

	$( '.bpghw_submit_widget' ).off().on( 'click', addWidget );

	// Save/clear presets
	function updatePresets(e){

		var clicked = e.target;
		var feedback = document.getElementById( 'bpghw_info' );
		var feedbackForm = document.getElementById( 'bpghw-widget-form' );
		feedbackForm.style.display = 'block';

		if ( clicked.name === 'clear' ) {
			feedback.innerHTML = bpghw_translate.clearingPreset;
			update = 'clear'
		} else {
			feedback.innerHTML = bpghw_translate.savingPreset;
			update = 'save';
		}
		$.ajax({
			url : ajax_object.ajaxurl,
			type : 'post',
			data : {
				update : update,
				security : ajax_object.check_nonce,
				action : "bpghw_update_presets"
			},
			success : function(data) {
				if ( data == 1 ) {
					feedback.innerHTML = bpghw_translate.success;
					if ( clicked.name === 'clear' ) {
						clicked.value = bpghw_translate.savePreset;
						clicked.name = 'save';
					} else {
						clicked.value = bpghw_translate.clearPreset;
						clicked.name = 'clear';
					}
				} else {
					feedback.innerHTML = bpghw_translate.tryAgain;
				}
				
			},
			error : function(data){
				feedback.innerHTML = bpghw_translate.tryAgain;
			}
		});
		
	}

	$( '#bpghw-update-preset' ).off().on( 'click', updatePresets );
});