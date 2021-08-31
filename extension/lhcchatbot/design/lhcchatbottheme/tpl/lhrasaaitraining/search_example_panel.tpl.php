<form ng-non-bindable action="<?php echo $input->form_action?>" method="get" name="SearchFormRight" autocomplete="off" class="mb-2">
    <input type="hidden" name="doSearch" value="1">
    <div class="row mb-2">
        <div class="col-md-2">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Verified');?></label>
            <select class="form-control form-control-sm" name="verified">
                <option value="">Any</option>
                <option value="1" <?php if ($input->verified === 1) : ?>selected="selected"<?php endif;?> ><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Yes');?></option>
                <option value="0"  <?php if ($input->verified === 0) : ?>selected="selected"<?php endif;?>><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','No');?></option>
            </select>
        </div>
        <div class="col-md-2">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Active');?></label>
            <select class="form-control form-control-sm" name="active">
                <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Any');?></option>
                <option value="1" <?php if ($input->active === 1) : ?>selected="selected"<?php endif;?> ><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Yes');?></option>
                <option value="0"  <?php if ($input->active === 0) : ?>selected="selected"<?php endif;?>><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','No');?></option>
            </select>
        </div>
    </div>
    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" name="doSearch" class="btn btn-secondary btn-sm" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />
    </div>
</form>
