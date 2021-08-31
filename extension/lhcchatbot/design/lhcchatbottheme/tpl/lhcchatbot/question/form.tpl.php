<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Possible questions');?></label>
    <textarea rows="5" cols="" class="form-control form-control-sm" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Each question has to be to separate line');?>" name="question"><?php echo htmlspecialchars($question->question)?></textarea>
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Answer');?></label>
    <input type="text" class="form-control form-control-sm" name="answer"  value="<?php echo htmlspecialchars($question->answer)?>" />
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Canned message');?></label>
    <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
        'input_name'     => 'canned_id',
        'optional_field' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('department/edit','Choose'),
        'display_name'   => 'title',
        'css_class'      => 'form-control form-control-sm',
        'selected_id'    => $question->canned_id,
        'list_function'  => 'erLhcoreClassModelCannedMsg::getList',
        'list_function_params'  => array('limit' => false,'filter' => array('department_id' => 0)),
    )); ?>
    <p><small><i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Manually written answer has a higher priority than canned message.')?></i></small></p>
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Context');?></label>
    <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
			'input_name'     => 'context_id',
			'optional_field' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('department/edit','Choose'),
			'display_name'   => 'name',
            'css_class'      => 'form-control form-control-sm',
			'selected_id'    => $question->context_id,
			'list_function'  => 'erLhcoreClassModelLHCChatBotContext::getList',
			'list_function_params'  => array_merge(array('limit' => '1000000'),((!empty($context_ids)) ? array('filterin' => array('id' => $context_ids)) : array())),
	)); ?>
</div>

<div class="form-group">
    <label><input type="checkbox" name="confirmed" value="on" <?php $question->confirmed == 1 ? print 'checked="checked"' : ''; ?> > <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Confirmed')?></label>
</div>