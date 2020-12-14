<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Name');?></label>
    <input type="text" class="form-control" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Name');?>" name="name" value="<?php echo htmlspecialchars($context->name)?>" />    
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Host');?></label>
    <input type="text" maxlength="150" class="form-control" placeholder="http://localhost:5000/model" name="host" value="<?php echo htmlspecialchars(!empty($context->host) ? $context->host : 'http://localhost:5000/model')?>" />
</div>