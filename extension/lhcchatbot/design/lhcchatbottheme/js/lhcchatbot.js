var lhcChatBot = {
    disabled : false,
    _currentRequest : null,
    unsupported : [],

    chatData : {

    },

    autoComplete: {
        'enabled':false,
        'loaded': false
    },

    timeoutComplete : null,
    lastQuery: null,

    replaceValue : function(elm, chat_id) {
        var valueNext = elm.value.replace('#' + lhcChatBot.lastQuery,'' + elm.chatterBotComplete);
        var valueOrig = elm.chatterBotComplete;
        Object.keys(lhcChatBot.chatData[chat_id]['placeholders']).forEach(function(key) {
            valueNext = valueNext.replace(key,lhcChatBot.chatData[chat_id]['placeholders'][key])
        });
        elm.value = valueNext;
        elm.chatterBotComplete = null;
        $('#suggest-completer-'+chat_id).text('...');

        $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggestused/' + chat_id, {
            'answer': valueOrig,
            'question': lhcChatBot.lastQuery,
            'context_id': 0,
            'aid': '',
            'type': 3
        });
    },

    appendCompletion : function(elm, chat_id, params) {
        var indexPosDot = elm.chatterBotComplete.indexOf('. ');
        var indexPosExc = elm.chatterBotComplete.indexOf('!');
        var indexPosQues = elm.chatterBotComplete.indexOf('?');

        params = params || {};

        var positions = [];

        if (indexPosDot > 0) {
            positions.push(indexPosDot);
        }

        if (indexPosExc > 0) {
            positions.push(indexPosExc);
        }

        if (indexPosQues > 0) {
            positions.push(indexPosQues);
        }

        var max = Math.min(...positions);

        var sentenes = elm.chatterBotComplete;
        var senteceOriginal = sentenes;

        if (typeof params['full'] === 'undefined' && max > 0) {
            sentenes = elm.chatterBotComplete.substring(0,max + 1);
            elm.chatterBotComplete = elm.chatterBotComplete.replace(sentenes,'');
            $('#suggest-completer-'+chat_id).text(elm.chatterBotComplete == '' ? '...' : elm.chatterBotComplete);
        } else {
            elm.chatterBotComplete = null;
            $('#suggest-completer-'+chat_id).text('...');
        }

        var valueNext = (elm.value + sentenes).replace('  ',' ');

        Object.keys(lhcChatBot.chatData[chat_id]['placeholders']).forEach(function(key) {
            valueNext = valueNext.replace(key,lhcChatBot.chatData[chat_id]['placeholders'][key])
        });

        elm.value = valueNext;

        $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggestused/' + chat_id, {
            'answer': lhcChatBot.lastQuery + senteceOriginal,
            'question': lhcChatBot.lastQuery,
            'context_id': 0,
            'aid': '',
            'type': 2
        });

    },

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
        } else if ((evt.which == 39 || evt.which == 9) && elm !== null && elm.value != '') {

            if (elm.value.length != elm.selectionStart) {
                return;
            }

            if (elm.value.length && elm.selectionStart && typeof elm.chatterBotComplete !== 'undefined' && elm.chatterBotComplete !== null && elm.chatterBotComplete !== '') {

                var chat_id = elm.getAttribute('id').replace('CSChatMessage-','');

                if (typeof elm.chatterBotReplace !== 'undefined' && elm.chatterBotReplace == true) {
                    lhcChatBot.replaceValue(elm, chat_id);
                } else {
                    lhcChatBot.appendCompletion(elm, chat_id);
                }

                evt.preventDefault();
            }

        } else if (elm !== null ) {

            if (lhcChatBot.autoComplete.loaded == false) {
                try {
                    if (typeof window["$_LHC_API"]['initial_data']['lhcchatbot'] !== 'undefined') {
                        lhcChatBot.autoComplete = window["$_LHC_API"]['initial_data']['lhcchatbot'];
                    }
                } catch (e) {}
                lhcChatBot.autoComplete.loaded = true;
            }

            var chat_id = elm.getAttribute('id').replace('CSChatMessage-','');

            if (!lhcChatBot.autoComplete.enabled || typeof lhcChatBot.chatData[chat_id] === 'undefined') {
                return;
            }

            clearTimeout(lhcChatBot.timeoutComplete);

            lhcChatBot.timeoutComplete = setTimeout(function () {

                if (lhcChatBot._currentRequest != null) {
                    lhcChatBot._currentRequest.abort();
                    lhcChatBot._currentRequest = null;
                }

                if (elm.value == '') {
                    lhcChatBot.lastQuery = '';
                    $('#suggest-completer-'+chat_id).text('...');
                    return;
                }

                // Chat data is missing already
                if (typeof lhcChatBot.chatData[chat_id] === 'undefined') {
                    return;
                }

                var parts = elm.value.trim().split(' ').splice(-3);

                // Make sure first word does not end with .
                if (parts[0].indexOf('.') !== -1 || parts[0].indexOf('!') !== -1 || parts[0].indexOf('?') !== -1) {
                    parts.shift();
                }

                if (parts.length == 2 && (parts[0].indexOf('.') !== -1 || parts[0].indexOf('!') !== -1 || parts[0].indexOf('?') !== -1)) {
                    parts.shift();
                }

                // Sentence ended in the midlde of the sentence
                if (parts.length == 3 && (parts[1].indexOf('.') !== -1 || parts[1].indexOf('!') !== -1 || parts[1].indexOf('?') !== -1)) {
                    parts.shift();
                    parts.shift();
                }

                var newSearch = parts.join(' ');

                var indexSearch = lhcChatBot.chatData[chat_id].index;

                var fullSearch = false;

                // We do not want to search by hashtags
                if (newSearch.indexOf('#') !== -1) {
                    indexSearch = indexSearch.replace('dep_','dep_hash_');
                    newSearch = newSearch.split('#').pop();
                    fullSearch = true;
                } else if (newSearch.indexOf('{') !== -1) {
                    newSearch = '{' + newSearch.split('{').pop();
                }

                // Make sure search is not the same as before one
                if (lhcChatBot.lastQuery == newSearch) {
                    return;
                }

                // Update last query
                lhcChatBot.lastQuery = newSearch;

                if (lhcChatBot.lastQuery == '') {
                    $('#suggest-completer-'+chat_id).text('...');
                    return;
                }

                var argsRequest = {
                    dataType: "json",
                    cache: true,
                    beforeSend: function(xhr, settings) {
                        xhr.setRequestHeader("X-Meili-API-Key", lhcChatBot.autoComplete.pKey);
                    },
                    url: lhcChatBot.autoComplete.mHost + '/indexes/' + indexSearch + '/search?limit=1&attributesToHighlight=*&q=' + encodeURIComponent(lhcChatBot.lastQuery)
                };

                if (lhcChatBot.autoComplete.overrideType) {
                    argsRequest.type = lhcChatBot.autoComplete.overrideType;
                }

                if (lhcChatBot.autoComplete.overrideData) {
                    lhcChatBot.autoComplete.overrideData.text = lhcChatBot.lastQuery;
                    argsRequest.data = JSON.stringify(lhcChatBot.autoComplete.overrideData);
                    argsRequest.contentType = "application/json; charset=utf-8";
                }

                if (lhcChatBot.autoComplete.overrideHost) {
                    argsRequest.url = lhcChatBot.autoComplete.overrideHost;
                }

                lhcChatBot._currentRequest = $.ajax(argsRequest).done(function(resp){
                    var chat_id = elm.getAttribute('id').replace('CSChatMessage-','');
                    var suggestionSet = false;

                    ee.emitEvent('lhcchatbot.autocomplete_transform', [resp]);

                    if (resp.hits.length > 0) {
                        var responseText = resp.hits[0]['question'];

                        var containerSuggest = $('#suggest-container-' + chat_id);

                        if ($('#suggest-container-' + chat_id).length == 0) {
                            $('#CSChatMessage-' + chat_id).parent().after('<div id="suggest-container-' + chat_id + '"><ul class="lhcchatbot-list list-inline"></ul></div>');
                        }

                        var containerSuggest = $('#suggest-completer-' + chat_id);
                        if ($('#suggest-completer-' + chat_id).length == 0) {
                            $('#suggest-container-' + chat_id+' > ul').prepend('<li class="list-inline-item ps-1 pb-1"><span class=" btn btn-sm btn-light text-secondary btn-send-success text-left" id="suggest-completer-' + chat_id + '"></span></li>');
                            $('#suggest-completer-' + chat_id).click(function () {
                                // Replace workflow
                                if (typeof elm.chatterBotReplace !== 'undefined' && elm.chatterBotReplace == true){
                                    lhcChatBot.replaceValue(elm, chat_id);
                                } else {
                                    lhcChatBot.appendCompletion(elm, chat_id, {full: true});
                                }
                                elm.focus();
                            });
                        }

                        if (fullSearch == true) {
                            $('#suggest-completer-'+chat_id).text((typeof resp.hits[0]['title'] !== 'undefined' ? resp.hits[0]['title'] + ' | ' : '')+responseText).attr('title',responseText);
                            var textArea = document.getElementById('CSChatMessage-'+chat_id);
                            textArea.chatterBotComplete = responseText;
                            textArea.chatterBotReplace = true;
                            suggestionSet = true;
                        } else {
                            var stringReplace = parts.join(' ').toLowerCase();
                            var indexStartReplace = responseText.toLowerCase().indexOf(stringReplace);

                            if (indexStartReplace === -1) {
                                stringReplace = parts.pop().toLowerCase();
                                indexStartReplace = responseText.toLowerCase().indexOf(stringReplace);
                            }

                            if (indexStartReplace !== -1) {
                                var complete = responseText.substring(indexStartReplace + stringReplace.length);
                                $('#suggest-completer-'+chat_id).text(complete == '' ? '...' : complete).attr('title',responseText);
                                var textArea = document.getElementById('CSChatMessage-'+chat_id);
                                textArea.chatterBotComplete = complete;
                                textArea.chatterBotReplace = false;
                                suggestionSet = true;
                            }
                        }
                    }

                    if (suggestionSet === false) {
                        $('#suggest-completer-'+chat_id).text('...');
                    }
                });
            }, 300);

        }
    },

    syncadmin: function (params,chatMode) {
        if (lhcChatBot.disabled == false && typeof params.chatbotids !== 'undefined') {
            $.getJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggest/(id)/' + params.chatbotids.join("/")+'/(chat)/'+(chatMode === true ? 1 : 0), function (data) {

                $.each(data.sg, function (chat_id, item) {

                    var messageArea = $('#CSChatMessage-'+chat_id);

                    // Perhaps chat was closed while waiting for resposne
                    if (messageArea.length == 0) {
                        return;
                    }

                    var containerSuggest = $('#suggest-container-' + chat_id);

                    if ($('#suggest-container-' + chat_id).length == 0) {
                        $('#CSChatMessage-' + chat_id).parent().after('<div id="suggest-container-' + chat_id + '"><ul class="lhcchatbot-list list-inline"></ul></div>');
                    }

                    containerSuggest = $('#suggest-container-' + chat_id).find('ul');

                    containerSuggest.find('button').removeClass('border-white');

                    $.each(item, function (i, itemSuggest) {
                        if ($('#' + chat_id + '-' + itemSuggest.aid).length == 0) {
                            messageArea.attr('placeholder',itemSuggest.a+' | →')[0].chatterBot = itemSuggest;
                            var li = jQuery('<li class="list-inline-item ps-1 pb-1 suggestion" ><button type="button" class="btn btn-sm text-secondary btn-light" title="Prefill message field" data-aid="' + itemSuggest.aid + '" data-ctx="' + itemSuggest.ctx + '" data-title="' + jQuery('<p/>').text(itemSuggest.a).html() + '" onclick="return lhcChatBot.prefill(' + chat_id + ',$(this))"><i class="material-icons me-0 fs11">edit</i></button> <button id="' + chat_id + '-' + itemSuggest.aid + '" data-aid="' + itemSuggest.aid + '" onclick="lhcChatBot.sendSuggest(' + chat_id + ',$(this))" style="max-width: 300px" title="' + jQuery('<p/>').text(itemSuggest.a).html() + '" type="button" data-ctx="' + itemSuggest.ctx + '" data-aid="' + itemSuggest.aid + '" class="btn btn-sm btn-light text-secondary border border-white text-truncate btn-send-success text-left">' + jQuery('<p/>').text(itemSuggest.a).html() + '</button> <button type="button" data-aid="' + itemSuggest.aid + '" data-ctx="' + itemSuggest.ctx + '" class="btn btn-xs btn-danger" title="' + jQuery('<p/>').text(itemSuggest.in_response).html() + '" onclick="return lhcChatBot.sendNegative(' + chat_id + ',$(this))"><i class="material-icons me-0 fs11">delete</i></button></li>').attr('title', jQuery('<p/>').text(itemSuggest.q).html());
                            var completer = $('#suggest-completer-'+chat_id);
                            if (completer.length > 0) {
                                completer.parent().after(li);
                            } else {
                                containerSuggest.prepend(li);
                            }
                        }
                    });

                    containerSuggest.find('li.suggestion:gt(6)').remove();

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
        var textarea = $("#CSChatMessage-" + chat_id);
        textarea.val(inst.text());

        lhinst.addmsgadmin(chat_id);

        $.postJSON(WWW_DIR_JAVASCRIPT + 'lhcchatbot/suggestused/' + chat_id, {
            'answer': inst.text(),
            'question': inst.parent().attr('title'),
            'context_id': inst.attr('data-ctx'),
            'aid': inst.attr('data-aid')
        }, function (data) {

        });

        inst.attr('disabled','disabled').prepend('<span class="material-icons lhc-spin">autorenew</span>');
        setTimeout(function(){
            inst.removeAttr('disabled').parent().remove();
        },1000);
        textarea.focus();
    },

    addCombination: function (inst, chat_id, event) {
        inst.parent().append('<form action="" onsubmit="return lhcChatBot.saveCombination(' + chat_id + ')"><i>&quot;' + jQuery('<p/>').text(lhcChatBot.selectedText).html() + '&quot;</i><div class="input-group mt-1"><input onclick="event.stopPropagation();" id="combination-chatter-' + chat_id + '" type="text" placeholder="Enter proposed answer" class="form-control form-control-sm" value=""><button onclick="lhcChatBot.saveCombination(' + chat_id + ')" class="btn btn-success btn-sm" type="button">Add</button></div></form>')
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
        elm.addEventListener('keydown', lhcChatBot.addListener);
    }
})

ee.addListener('quoteAction', function (params, chat_id) {
    if (lhcChatBot.unsupported.indexOf(chat_id) === -1 && lhcChatBot.disabled == false) {
        lhcChatBot.selectedText = lhinst.getSelectedTextPlain();
        var contentOriginal = params['content']();
        params['content'] = function(){ return contentOriginal + ' | <a href="#" id="add-suggestion-popover-'+chat_id+'"><i class="material-icons me-0">library_add</i></a>' }
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

    try {
        delete lhcChatBot.chatData[chat_id];
    } catch (e){}

    // Remove event listener
    var elm = document.getElementById('CSChatMessage-'+chat_id);
    if (elm !== null) {
        elm.removeEventListener('keydown', lhcChatBot.addListener);
    }
});

ee.addListener('eventLoadInitialData', function(initialData) {
    if (initialData.lhcchatbot) {
        lhcChatBot.autoComplete = initialData.lhcchatbot;
        lhcChatBot.autoComplete.loaded = true;
    }
});

ee.addListener('mainChatDataLoaded', function(chat_id, data) {
    if (typeof data.data_ext !== 'undefined' && typeof data.data_ext.lhcchatbot !== 'undefined') {
        lhcChatBot.chatData[chat_id] = data.data_ext.lhcchatbot;
    }
});

