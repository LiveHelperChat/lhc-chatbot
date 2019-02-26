var lhcChatBot = {
	syncadmin : function(params) {
		if (typeof params.chatbotids !== 'undefined') {
			$.getJSON(WWW_DIR_JAVASCRIPT  + 'lhcchatbot/suggest/(id)/'+params.chatbotids.join("/"), function(data) {

				$.each(data.sg,function(chat_id, item) {

					var containerSuggest = $('#suggest-container-' + chat_id);

					if ($('#suggest-container-' + chat_id).length == 0) {
						$('#CSChatMessage-'+chat_id).after('<div id="suggest-container-'+chat_id+'"><ul class="lhcchatbot-list list-inline"></ul></div>');
					}

					containerSuggest = $('#suggest-container-' + chat_id).find('ul');

					containerSuggest.find('li').removeClass('lhc-new-suggest');

					$.each(item,function(i, itemSuggest) {
					    if ($('#'+chat_id+'-'+itemSuggest.aid).length == 0) {
                            var li = jQuery('<li class="lhc-new-suggest list-inline-item pl-1" ><button id="'+chat_id+'-'+itemSuggest.aid+'" onclick="lhcChatBot.sendSuggest('+chat_id+',$(this))" type="button" class="btn btn-xs btn-info">'+jQuery('<p/>').text(itemSuggest.a).html()+'</button> <button type="button" class="btn btn-xs btn-danger" title="'+jQuery('<p/>').text(itemSuggest.in_response).html()+'" onclick="return lhcChatBot.sendNegative('+chat_id+',$(this))"><i class="material-icons mr-0 fs11">delete</i></button></li>').attr('title', jQuery('<p/>').text(itemSuggest.q).html());
                            containerSuggest.prepend(li);
                        }
					});

					containerSuggest.find('li:gt(4)').remove();
				});
	        });
		}
	},

    sendNegative : function(chat_id, inst) {
        $.postJSON(WWW_DIR_JAVASCRIPT  + 'lhcchatbot/suggestinvalid/'+chat_id, {'answer' : inst.parent().find('.btn-info').text(), 'question' : inst.attr('title')}, function(data) {
            inst.find('.material-icons').text('check');
        });
        return false;
    },

	sendSuggest : function(chat_id,inst) {
		// Send message
		$("#CSChatMessage-"+chat_id).val(inst.text());
		lhinst.addmsgadmin(chat_id);

        $.postJSON(WWW_DIR_JAVASCRIPT  + 'lhcchatbot/suggestused/'+chat_id, {'answer' : inst.text(), 'question' : inst.attr('title')}, function(data) {

        });
	}
};

ee.addListener('eventSyncAdmin', function(params){
	lhcChatBot.syncadmin(params);
});