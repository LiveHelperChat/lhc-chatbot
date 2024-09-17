<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Name');?></label>
    <input type="text" class="form-control form-control-sm" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Name');?>" name="name" value="<?php echo htmlspecialchars($context->name)?>" />
</div>

<?php if (!class_exists('erLhcoreClassInstance')) : ?>

<div class="form-group" ng-non-bindable>
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Host');?></label>
    <input type="text" maxlength="150" class="form-control form-control-sm" placeholder="http://localhost:5000/model" name="host" value="<?php echo htmlspecialchars(!empty($context->host) ? $context->host : 'http://localhost:5000/model')?>" />
    <p><small><i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','DeepPavlov host for reply predictions if MeiliSearch is not used.');?></i></small></p>
</div>

<div class="form-group">
    <select class="form-control form-control-sm" name="meili">
        <option value="0">DeepPavlov</option>
        <option value="1" <?php if ($context->meili == 1) : ?>selected="selected"<?php endif;?>>Meili search</option>
        <option value="2" <?php if ($context->meili == 2) : ?>selected="selected"<?php endif;?>>Rasa Intent</option>
    </select>
</div>

<?php endif; ?>