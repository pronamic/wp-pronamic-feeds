var Pronamic_Feeds_Admin = {
	config: {},
	ready:function(){
		// autoloads
		Pronamic_Feeds_Admin.message.ready();
	},
	rss:{
		config:{},
		ready:function(){
			Pronamic_Feeds_Admin.rss.config.dom = {
				'add_message_button' : jQuery('.jAddMessage')
			};

			Pronamic_Feeds_Admin.rss.binds();
		},
		binds:function(){
			Pronamic_Feeds_Admin.rss.config.dom.add_message_button.click( Pronamic_Feeds_Admin.rss.add_message );
		},
		add_message:function(e){
			e.preventDefault();

			Pronamic_Feeds_Admin.rss.config.current_button = jQuery( this );

			Pronamic_Feeds_Admin.rss.show_spinner();

            var message_id      = jQuery( this ).data( 'id' ),
                hashed_id       = jQuery( this ).data( 'hashedid' ),
                feed_url        = jQuery( this ).data( 'url' ),
                feed_id         = jQuery( this ).data( 'feedid' );

			jQuery.ajax({
				url:ajaxurl,
				type:'POST',
				data:{
					action:'add_message',
					message_id: message_id,
					hashed_id: hashed_id,
					feed_url: feed_url,
					feed_id: feed_id
				},
				dataType:'json',
				success:Pronamic_Feeds_Admin.rss.add_message_success
			});
		},
		add_message_success:function(data){
			Pronamic_Feeds_Admin.message.flash(data.type, data.title, data.message);
			Pronamic_Feeds_Admin.rss.hide_spinner();
		},
		show_spinner:function(){
			var spinner = jQuery('<img/>');
			spinner.attr('src', Pronamic_Feeds_Admin.config.spinner);

			Pronamic_Feeds_Admin.rss.config.current_button.html(spinner);
		},
		hide_spinner:function(){
			Pronamic_Feeds_Admin.rss.config.current_button.empty();
		}
	},
	message:{
		config:{},
		ready:function(){
			Pronamic_Feeds_Admin.message.config.dom = {
				message_holder: jQuery('.message_holder')
			}
		},
		flash:function(type, title, message){
			var message_holder = jQuery( '<div></div>' )
				.addClass( 'alert' )
				.addClass('alert-' + type );

			var message_title = jQuery( '<h4></h4>' ).html( title );

			message_holder.append(message_title).append(message);

			Pronamic_Feeds_Admin.message.config.dom.message_holder.append(message_holder);
		}
	}
}

jQuery(Pronamic_Feeds_Admin.ready);