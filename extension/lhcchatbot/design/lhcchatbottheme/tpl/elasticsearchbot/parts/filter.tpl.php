<form action="<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/list')?>" method="get" name="SearchFormRight">
	<input type="hidden" name="doSearch" value="1">

	<div class="row">

		<div class="col-md-2">
		  <div class="form-group">
			<label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Chat ID');?></label>
			<input type="text" class="form-control" name="chat_id" value="<?php echo htmlspecialchars($input->chat_id)?>" />
		  </div>
		</div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Status');?></label>
                <select name="confirmed" class="form-control">
                    <option value="">All</option>
                    <option value="1" <?php $input->confirmed == 1 ? print 'selected="selected"' : '' ?> >Confirmed</option>
                    <option value="0" <?php $input->confirmed === 0 ? print 'selected="selected"' : '' ?> >Unconfirmed</option>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Department');?></label>
                <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
                    'input_name'     => 'department_id',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select department'),
                    'selected_id'    => $input->department_id,
                    'css_class'      => 'form-control',
                    'list_function'  => 'erLhcoreClassModelDepartament::getList'
                )); ?>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Sort');?></label>
                <select name="sort" class="form-control">
                    <option value="new" <?php ($input->sort == 'new' || $input->sort == '') ? print 'selected="selected"' : '';?> >Newest first</option>
                    <option value="match_count" <?php $input->sort == 'match_count' ? print 'selected="selected"' : '';?> >Most matches</option>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Show');?></label>
                <select name="hidden" class="form-control">
                    <option value="">All</option>
                    <option value="1" <?php $input->hidden == 1 ? print 'selected="selected"' : '' ?> >Only hidden</option>
                    <option value="0" <?php $input->hidden === 0 ? print 'selected="selected"' : '' ?> >Only un-hidden</option>
                </select>
            </div>
        </div>

	</div>



	<div class="btn-group" role="group" aria-label="...">
		<input type="submit" name="doSearch" class="btn btn-default" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />
	</div>
</form>