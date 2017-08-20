<?php if ($tab == 'messages') : ?>
<div class="pull-right">
    Records in total - <?php echo $total_literal;?>
</div>
<?php endif; ?>

<form action="<?php echo erLhcoreClassDesign::baseurl('elasticsearch/list')?>/(tab)/messages#/messages" method="get" name="SearchFormRight">
	<input type="hidden" name="doSearch" value="1">
	<div class="row">	
		<div class="col-md-2">
		  <div class="form-group">
			<label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Message text');?></label>
			<input type="text" class="form-control" name="message_text" value="<?php echo htmlspecialchars($input_msg->message_text)?>" />
		  </div>
		</div>
        <div class="col-md-2">
		  <div class="form-group">
			<label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Sort');?></label>
			<select name="sort_msg" class="form-control">
                <option value="relevance" <?php ($input_msg->sort_msg == 'relevance' || $input_msg->sort_msg == '') ? print 'selected="selected"' : null?> >Relevance</option>
                <option value="desc" <?php ($input_msg->sort_msg == 'desc') ? print 'selected="selected"' : null?> >From new to old</option>
                <option value="asc" <?php $input_msg->sort_msg == 'asc' ? print 'selected="selected"' : null?> >From old to new</option>
            </select>
		  </div>
		</div>
	</div>
	<div class="btn-group" role="group" aria-label="...">
		<input type="submit" name="doSearch" class="btn btn-default" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />	
	</div>
</form>

