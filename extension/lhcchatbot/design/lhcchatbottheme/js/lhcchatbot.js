var lhcChatBot = {
	syncadmin : function(params) {
		if (typeof params.chatbotids !== 'undefined') {
			$.getJSON(WWW_DIR_JAVASCRIPT  + 'lhcchatbot/suggest/(id)/'+params.chatbotids.join("/"), function(data) {

				$.each(data.sg,function(chat_id, item) {

					var containerSuggest = $('#suggest-container-' + chat_id);

					if ($('#suggest-container-' + chat_id).size() == 0) {
						$('#CSChatMessage-'+chat_id).after('<div id="suggest-container-'+chat_id+'"><ul class="lhcchatbot-list list-inline"></ul></div>');
					}

					containerSuggest = $('#suggest-container-' + chat_id).find('ul');

					$.each(item,function(i, itemSuggest) {
						containerSuggest.prepend('<li onclick="lhcChatBot.sendSuggest('+chat_id+',$(this))">'+jQuery('<p/>').text(itemSuggest).html()+'</li>');
					});

					containerSuggest.find('li:gt(4)').remove();
				});
	        });
		}
	},

	sendSuggest : function(chat_id,inst) {
		// Send message
		$("#CSChatMessage-"+chat_id).val(inst.text());
		lhinst.addmsgadmin(chat_id);
	}
};

ee.addListener('eventSyncAdmin', function(params){
	lhcChatBot.syncadmin(params);
});