<form action="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/list')?>" method="get" name="SearchFormRight">
    <input type="hidden" name="doSearch" value="1">

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Context');?></label>
                <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
                    'input_name'     => 'context_id',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select context'),
                    'selected_id'    => $input->context_id,
                    'css_class'      => 'form-control',
                    'list_function'  => 'erLhcoreClassModelLHCChatBotContext::getList'
                )); ?>
            </div>
        </div>
    </div>

    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" name="doSearch" class="btn btn-default" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />
    </div>
</form>