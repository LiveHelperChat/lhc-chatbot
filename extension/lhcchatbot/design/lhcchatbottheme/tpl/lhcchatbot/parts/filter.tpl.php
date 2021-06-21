<form action="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/list')?>" method="get" name="SearchFormRight" class="mb-2" ng-non-bindable>
    <input type="hidden" name="doSearch" value="1">

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Context');?></label>
                <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
                    'input_name'     => 'context_id',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select context'),
                    'selected_id'    => $input->context_id,
                    'css_class'      => 'form-control form-control-sm',
                    'list_function'  => 'erLhcoreClassModelLHCChatBotContext::getList',
                    'list_function_params' => ((!empty($context_ids)) ? array('filterin' => array('id' => $context_ids)) : array())
                )); ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Status');?></label>
                <select name="confirmed" class="form-control form-control-sm">
                    <option value="">All</option>
                    <option value="1" <?php $input->confirmed == 1 ? print 'selected="selected"' : '' ?> >Confirmed</option>
                    <option value="0" <?php $input->confirmed === 0 ? print 'selected="selected"' : '' ?> >Unconfirmed</option>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Keyword question');?></label>
                <input type="text" name="keyword" class="form-control form-control-sm" value="<?php echo htmlspecialchars($input->keyword)?>" />
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Keyword answer');?></label>
                <input type="text" name="keyword_answer" class="form-control form-control-sm" value="<?php echo htmlspecialchars($input->keyword_answer)?>" />
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Sort');?></label>
                <select class="form-control form-control-sm" name="sort">
                    <option value="newfirst" <?php $input->sort == 'newfirst' ? print 'selected="selected"' : ''?> >Newest</option>
                    <option value="oldfirst" <?php $input->sort == 'oldfirst' ? print 'selected="selected"' : ''?> >Oldest</option>
                    <option value="wasused" <?php $input->sort == 'wasused' ? print 'selected="selected"' : ''?> >Most used</option>
                </select>
            </div>
        </div>

    </div>

    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" name="doSearch" class="btn btn-secondary" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />
    </div>
</form>