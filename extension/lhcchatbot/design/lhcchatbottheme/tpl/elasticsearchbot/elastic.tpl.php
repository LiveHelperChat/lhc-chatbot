<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Elastic search console')?></h1>
<form action="" method="post">
    <div class="form-group">
		<label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Index')?></label> 
		<input type="text" class="form-control" name="Index" value="<?php echo htmlspecialchars($index)?>">
	</div>

    <div class="form-group">
		<label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Type')?></label> 
		<input type="text" class="form-control" name="Type" value="<?php echo htmlspecialchars($type)?>">
	</div>

    <textarea class="form-control" style="font-size:11px" rows="10" name="Query"><?php echo htmlspecialchars($command)?></textarea>
    <br>
    <input type="submit" name="doSearch" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Submit')?>" class="btn btn-default" />
</form>
<br/>
<pre style="font-size:11px;">
<?php echo htmlspecialchars(print_r($response,true))?>
</pre>