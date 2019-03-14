var lhcChatBot = {
    disabled : false,
    unsupported : [],
    syncadmin: function (params,chatMode) {
        if (lhcChatBot.disabled == false && typeof params.chatbotids !== 'undefined') {
            $.getJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggest/(id)/' + params.chatbotids.join("/")+'/(chat)/'+(chatMode === true ? 1 : 0), function (data) {

                $.each(data.sg, function (chat_id, item) {

                    var containerSuggest = $('#suggest-container-' + chat_id);

                    if ($('#suggest-container-' + chat_id).length == 0) {
                        $('#CSChatMessage-' + chat_id).after('<div id="suggest-container-' + chat_id + '"><ul class="lhcchatbot-list list-inline"></ul></div>');
                    }

                    containerSuggest = $('#suggest-container-' + chat_id).find('ul');

                    containerSuggest.find('li').removeClass('lhc-new-suggest');

                    $.each(item, function (i, itemSuggest) {
                        if ($('#' + chat_id + '-' + itemSuggest.aid).length == 0) {
                            var li = jQuery('<li class="lhc-new-suggest list-inline-item pl-1 pb-1" ><button id="' + chat_id + '-' + itemSuggest.aid + '" onclick="lhcChatBot.sendSuggest(' + chat_id + ',$(this))" type="button" class="btn btn-sm btn-light text-left">' + jQuery('<p/>').text(itemSuggest.a).html() + '</button> <button type="button" class="btn btn-xs btn-danger" title="' + jQuery('<p/>').text(itemSuggest.in_response).html() + '" onclick="return lhcChatBot.sendNegative(' + chat_id + ',$(this))"><i class="material-icons mr-0 fs11">delete</i></button></li>').attr('title', jQuery('<p/>').text(itemSuggest.q).html());
                            containerSuggest.prepend(li);
                        }
                    });

                    containerSuggest.find('li:gt(6)').remove();
                });

                $.each(data.un, function (index,chat_id) {
                    if (lhcChatBot.unsupported.indexOf(chat_id) === -1) {
                        lhcChatBot.unsupported.push(chat_id);
                    }
                })
            });
        }
    },

    sendNegative: function (chat_id, inst) {
        $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggestinvalid/' + chat_id, {
            'answer': inst.parent().find('.btn-info').text(),
            'question': inst.attr('title')
        }, function (data) {
            inst.parent().remove();
        });
        return false;
    },

    sendSuggest: function (chat_id, inst) {
        // Send message
        $("#CSChatMessage-" + chat_id).val(inst.text());
        lhinst.addmsgadmin(chat_id);

        $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggestused/' + chat_id, {
            'answer': inst.text(),
            'question': inst.attr('title')
        }, function (data) {

        });
    },

    addCombination: function (inst, chat_id, event) {
        inst.parent().append('<form action="" onsubmit="return lhcChatBot.saveCombination(' + chat_id + ')"><i>&quot;' + jQuery('<p/>').text(lhcChatBot.selectedText).html() + '&quot;</i><div class="input-group mt-1"><input onclick="event.stopPropagation();" id="combination-chatter-' + chat_id + '" type="text" placeholder="Enter proposed answer" class="form-control form-control-sm" value=""><div class="input-group-prepend"><button onclick="lhcChatBot.saveCombination(' + chat_id + ')" class="btn btn-success btn-sm" type="button">Add</button></div></div></form>')
        event.stopPropagation();
        $('#combination-chatter-' + chat_id).focus();
        return false;
    },

    saveCombination: function (chat_id) {
        $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/savecombination/' + chat_id, {
            'answer': $('#combination-chatter-' + chat_id).val(),
            'question': lhcChatBot.selectedText
        }, function (data) {
            if (data.error == true) {
                alert(data.msg);
            } else {
                $('#combination-chatter-'+chat_id).parent().replaceWith(data.result);
                lhcChatBot.syncadmin({chatbotids:[chat_id]},true);

            }
        });
        return false;
    },

    selectedText: null
};

ee.addListener('eventSyncAdmin', function (params) {
    if (lhcChatBot.disabled == false) {
        lhcChatBot.syncadmin(params,false);
    }
});

ee.addListener('quoteAction', function (params, chat_id) {
    if (lhcChatBot.unsupported.indexOf(chat_id) === -1 && lhcChatBot.disabled == false) {
        lhcChatBot.selectedText = lhinst.getSelectedTextPlain();
        params['content'] = params['content'] + ' | <a href="#" onclick="return lhcChatBot.addCombination($(this),' + chat_id + ',event);"><i class="material-icons mr-0">library_add</i></a>'
    }
});

ee.addListener('removeSynchroChat', function(chat_id) {
    var index = lhcChatBot.unsupported.indexOf(chat_id)
    if (index !== -1) {
        lhcChatBot.unsupported.splice(index,1);
    }
});