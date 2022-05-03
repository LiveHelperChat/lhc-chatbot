<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('rasatraining/form','Name');?></label>
    <input maxlength="50" type="text" placeholder="E.g hello English" ng-non-bindable class="form-control form-control-sm" name="name" value="<?php echo htmlspecialchars($item->name);?>" />
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Context');?></label>
    <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
        'input_name'     => 'context_id',
        'optional_field' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('department/edit','Choose'),
        'display_name'   => 'name',
        'css_class'      => 'form-control form-control-sm',
        'selected_id'    => $item->context_id,
        'list_function'  => 'erLhcoreClassModelLHCChatBotContext::getList',
        'list_function_params'  => array(),
    )); ?>
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('rasatraining/form','Intent');?></label>
    <input maxlength="50" type="text" placeholder="E.g hello" ng-non-bindable class="form-control form-control-sm" name="intent" value="<?php echo htmlspecialchars($item->intent);?>" />
</div>

<div class="form-group">
    <label>
        <input type="checkbox" value="on" name="active" <?php if ($item->active == 1) : ?>checked="checked"<?php endif;?> />
        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('rasatraining/form','Active')?>
    </label>
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Test samples for verification');?></label>
    <textarea rows="10" cols="" class="form-control form-control-sm" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Each sample has to be to separate by a new line');?>" name="test_samples"><?php echo htmlspecialchars($item->test_samples)?></textarea>
</div>