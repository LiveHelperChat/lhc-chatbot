var lhcChatBot = {
    disabled : false,
    unsupported : [],

    addListener : function (evt) {
        var elm = evt.currentTarget;
        if (evt.which == 39 && elm !== null && elm.value == '' && typeof elm.chatterBot !== 'undefined') {
            elm.value = elm.chatterBot.a;
            var chat_id = elm.getAttribute('id').replace('CSChatMessage-','');
            $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggestused/' + chat_id, {
                'answer': elm.chatterBot.a,
                'question': elm.chatterBot.q,
                'context_id': elm.chatterBot.ctx,
                'aid': elm.chatterBot.aid,
                'type': 1
            });
        }
    },

    syncadmin: function (params,chatMode) {
        if (lhcChatBot.disabled == false && typeof params.chatbotids !== 'undefined') {
            $.getJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggest/(id)/' + params.chatbotids.join("/")+'/(chat)/'+(chatMode === true ? 1 : 0), function (data) {

                $.each(data.sg, function (chat_id, item) {

                    var containerSuggest = $('#suggest-container-' + chat_id);

                    if ($('#suggest-container-' + chat_id).length == 0) {
                        $('#CSChatMessage-' + chat_id).parent().after('<div id="suggest-container-' + chat_id + '"><ul class="lhcchatbot-list list-inline"></ul></div>');
                    }

                    containerSuggest = $('#suggest-container-' + chat_id).find('ul');

                    containerSuggest.find('li').removeClass('lhc-new-suggest');

                    $.each(item, function (i, itemSuggest) {
                        if ($('#' + chat_id + '-' + itemSuggest.aid).length == 0) {
                            $('#CSChatMessage-'+chat_id).attr('placeholder',itemSuggest.a+' | →')[0].chatterBot = itemSuggest;
                            var li = jQuery('<li class="lhc-new-suggest list-inline-item pl-1 pb-1" ><button type="button" class="btn btn-sm btn-light" title="Prefill message field" data-aid="' + itemSuggest.aid + '" data-ctx="' + itemSuggest.ctx + '" data-title="' + jQuery('<p/>').text(itemSuggest.a).html() + '" onclick="return lhcChatBot.prefill(' + chat_id + ',$(this))"><i class="material-icons mr-0 fs11">edit</i></button> <button id="' + chat_id + '-' + itemSuggest.aid + '" data-aid="' + itemSuggest.aid + '" onclick="lhcChatBot.sendSuggest(' + chat_id + ',$(this))" type="button" data-ctx="' + itemSuggest.ctx + '" data-aid="' + itemSuggest.aid + '" class="btn btn-sm btn-light btn-send-success text-left">' + jQuery('<p/>').text(itemSuggest.a).html() + '</button> <button type="button" data-aid="' + itemSuggest.aid + '" data-ctx="' + itemSuggest.ctx + '" class="btn btn-xs btn-danger" title="' + jQuery('<p/>').text(itemSuggest.in_response).html() + '" onclick="return lhcChatBot.sendNegative(' + chat_id + ',$(this))"><i class="material-icons mr-0 fs11">delete</i></button></li>').attr('title', jQuery('<p/>').text(itemSuggest.q).html());
                            containerSuggest.prepend(li);
                        }
                    });

                    containerSuggest.find('li:gt(6)').remove();

                    // Adjust scroll by the height of container
                    $('#messagesBlock-'+chat_id).prop('scrollTop',$('#messagesBlock-'+chat_id).prop('scrollTop')+$('#suggest-container-'+chat_id).height());

                });

                $.each(data.un, function (index,chat_id) {
                    if (lhcChatBot.unsupported.indexOf(chat_id) === -1) {
                        lhcChatBot.unsupported.push(chat_id);
                    }
                })
            });
        }
    },

    prefill : function(chat_id,inst)
    {
        $("#CSChatMessage-" + chat_id).val(inst.attr('data-title'));

        $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggestused/' + chat_id, {
            'answer': inst.attr('data-title'),
            'question': inst.parent().attr('title'),
            'context_id': inst.attr('data-ctx'),
            'aid': inst.attr('data-aid')
        }, function (data) {

        });
    },

    sendNegative: function (chat_id, inst) {
        $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggestinvalid/' + chat_id, {
            'answer': inst.parent().find('.btn-send-success').text(),
            'question': inst.attr('title'),
            'context': inst.attr('data-ctx'),
            'aid': inst.attr('data-aid')
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
            'question': inst.parent().attr('title'),
            'context_id': inst.attr('data-ctx'),
            'aid': inst.attr('data-aid')
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

ee.addListener('adminChatLoaded', function (chat_id) {
    var elm = document.getElementById('CSChatMessage-'+chat_id);
    if (elm !== null) {
        elm.addEventListener('keyup', lhcChatBot.addListener);
    }
})

ee.addListener('quoteAction', function (params, chat_id) {
    if (lhcChatBot.unsupported.indexOf(chat_id) === -1 && lhcChatBot.disabled == false) {
        lhcChatBot.selectedText = lhinst.getSelectedTextPlain();
        var contentOriginal = params['content']();
        params['content'] = function(){ return contentOriginal + ' | <a href="#" id="add-suggestion-popover-'+chat_id+'"><i class="material-icons mr-0">library_add</i></a>' }
        // Add event listener
        setTimeout(function(){
            $('#add-suggestion-popover-'+chat_id).click(function (event){
                lhcChatBot.addCombination($(this), chat_id, event);
            });
        },400);
    }
});

ee.addListener('removeSynchroChat', function(chat_id) {

    var index = lhcChatBot.unsupported.indexOf(chat_id);
    if (index !== -1) {
        lhcChatBot.unsupported.splice(index,1);
    }

    // Remove event listener
    var elm = document.getElementById('CSChatMessage-'+chat_id);
    if (elm !== null) {
        elm.removeEventListener('keyup', lhcChatBot.addListener);
    }
});