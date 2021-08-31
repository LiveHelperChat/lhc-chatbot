<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Name');?></label>
    <input type="text" class="form-control" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Name');?>" name="name" value="<?php echo htmlspecialchars($context->name)?>" />    
</div>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Host');?></label>
    <input type="text" maxlength="150" class="form-control" placeholder="http://localhost:5000/model" name="host" value="<?php echo htmlspecialchars(!empty($context->host) ? $context->host : 'http://localhost:5000/model')?>" />
</div>

<div class="form-group">
    <label>
        <input type="checkbox" value="on" name="meili" <?php if ($context->meili == 1) : ?>checked="checked"<?php endif;?> />
        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('rasatraining/form','Use MeiliSearch instead of DeepPavlov')?>
    </label>
</div>