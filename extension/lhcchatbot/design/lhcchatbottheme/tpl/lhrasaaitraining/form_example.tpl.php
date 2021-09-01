<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Intent');?></label>
    <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
        'input_name'     => 'intent_id',
        'optional_field' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('department/edit','Choose'),
        'display_name'   => function($item) {
            return $item->name . ' ' . ($item->intent);
        },
        'css_class'      => 'form-control form-control-sm',
        'selected_id'    => $item->intent_id,
        'list_function'  => 'erLhcoreClassModelLHCChatBotRasaIntent::getList',
        'list_function_params'  => array(),
    )); ?>
</div>

<div class="form-group">
    <label>
        <input type="checkbox" value="on" name="verified" <?php if ($item->verified == 1) : ?>checked="checked"<?php endif;?> />
        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('rasatraining/form','Verified')?>
    </label>
    <p><small><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','If question will come from third party it makes sense to mark them as unverified. So operators will know this training needs attention.');?></small></p>
</div>

<div class="form-group">
    <label>
        <input type="checkbox" value="on" name="active" <?php if ($item->active == 1) : ?>checked="checked"<?php endif;?> />
        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('rasatraining/form','Active')?>
    </label>
    <p><small><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Once you are happy with training make it active and verified for retraining.');?></small></p>
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Possible questions');?></label>
    <textarea rows="15" cols="" class="form-control form-control-sm" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Each question has to be to separate by a new line');?>" name="example"><?php echo htmlspecialchars($item->example)?></textarea>
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Comment');?></label>
    <textarea rows="15" cols="" readonly class="form-control form-control-sm" name="comment"><?php echo htmlspecialchars($item->comment)?></textarea>
</div>