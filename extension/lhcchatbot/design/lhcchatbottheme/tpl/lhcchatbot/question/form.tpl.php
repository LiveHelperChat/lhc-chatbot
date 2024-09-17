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
    <?php echo erLhcoreClassRenderHelper::renderMultiDropdown( array (
        'input_name'     => 'canned_id',
        'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Canned message'),
        'selected_id'    => [$question->canned_id],
        'ajax'           => 'canned',
        'data_prop'      => 'data-limit="1" data-type="radio" data-noselector="1"',
        'css_class'      => 'form-control',
        'type'           => 'radio',
        'display_name'   => 'title',
        'no_selector'    => true,
        'list_function_params' => array('sort' => 'title ASC', 'limit' => 10, 'filter' => array('department_id' => 0)),
        'list_function'  => 'erLhcoreClassModelCannedMsg::getList',
    )); ?>
    <script>
        $(function() {
            $('.btn-block-department').makeDropdown();
        });
    </script>
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

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Intent if Rasa AI is used for FAQ');?></label>
    <?php echo erLhcoreClassRenderHelper::renderCombobox(array(
			'input_name'     => 'rasa_intent_id',
			'optional_field' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('department/edit','Choose'),
			'display_name'   => 'intent',
            'css_class'      => 'form-control form-control-sm',
			'selected_id'    => $question->rasa_intent_id,
			'list_function'  => 'erLhcoreClassModelLHCChatBotRasaIntent::getList',
			'list_function_params'  => array('limit' => '1000000','filter' => array('context_id' => $question->context_id))
	)); ?>
</div>
<p>Save question before choosing intent.</p>

<div class="form-group">
    <label><input type="checkbox" name="confirmed" value="on" <?php $question->confirmed == 1 ? print 'checked="checked"' : ''; ?> > <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Confirmed')?></label>
</div>