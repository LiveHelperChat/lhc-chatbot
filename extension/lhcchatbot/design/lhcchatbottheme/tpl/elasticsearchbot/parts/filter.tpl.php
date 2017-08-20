<form action="<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/list')?>" method="get" name="SearchFormRight">
	<input type="hidden" name="doSearch" value="1">
	<div class="row">	
		<div class="col-md-2">
		  <div class="form-group">
			<label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('bracket/lists/filter','Chat ID');?></label>
			<input type="text" class="form-control" name="chat_id" value="<?php echo htmlspecialchars($input->chat_id)?>" />
		  </div>
		</div>
	</div>
	<div class="btn-group" role="group" aria-label="...">
		<input type="submit" name="doSearch" class="btn btn-default" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />
	</div>
</form>