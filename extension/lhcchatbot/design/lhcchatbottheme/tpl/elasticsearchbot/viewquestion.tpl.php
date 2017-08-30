<h1>View question</h1>

<div class="form-group">
    <label>View question, base on <a href="#" onclick="lhc.previewChat(<?php echo $question->chat_id?>)"><i class="material-icons">info_outline</i></a> <?php  echo $question->chat_id?> chat</label>
    <input type="text" class="form-control" name="question" value="<?php echo htmlspecialchars($question->question)?>" />
</div>

<ul class="list-unstyled">
    <?php foreach ($items as $item) : ?>
        <li><a title="<?php echo $question->chat_id?>" href="#" onclick="lhc.previewChat(<?php echo $question->chat_id?>)"><i class="material-icons">info_outline</i></a> <a class="material-icons" data-text="<?php echo htmlspecialchars($item->answer)?>" onclick="$('#answer-chatbot').val($(this).attr('data-text'));" title="Copy to answer">&#xE14D;</a> [<?php echo $item->match_count?>] - <?php echo htmlspecialchars($item->answer)?></li>
    <?php endforeach; ?>
</ul>

<hr>
<?php if (isset($updated) && $updated == true) : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Updated!'); ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<form action="" method="post">

    <p><i class="material-icons<?php $question->confirmed == 1 ? print ' chat-active' : ' chat-unread'?>">&#xE5CA;</i><?php $question->confirmed == 1 ? print '' : print 'Not'?> confirmed -  <small><i>(It will became confirmed once you save question with answer.)</i></small></p>

    <div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Question');?></label>
        <input type="text" class="form-control" name="question"  value="<?php echo htmlspecialchars($question->cbot_question->question == '' ? $question->question : $question->cbot_question->question)?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Answer');?></label>
        <input type="text" class="form-control" name="answer" id="answer-chatbot" value="<?php echo htmlspecialchars($question->cbot_question->answer)?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Context');?></label>
        <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
            'input_name'     => 'context_id',
            'optional_field' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('department/edit','None'),
            'display_name'   => 'name',
            'css_class'      => 'form-control',
            'selected_id'    => $question->cbot_question->context_id,
            'list_function'  => 'erLhcoreClassModelLHCChatBotContext::getList',
            'list_function_params'  => array_merge(array('limit' => '1000000')),
        )); ?>
    </div>

    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" class="btn btn-default" name="Save_action" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save');?>"/>
    </div>

</form>