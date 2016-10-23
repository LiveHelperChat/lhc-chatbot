<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Test')?></h1>
<p><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Make sure you have entered some questions')?></p>

<form action="" method="get">

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/form','Question');?></label>
        <input type="text" class="form-control" name="question"  value="<?php isset($_GET['question']) ? print htmlspecialchars($_GET['question']) : ''?>" />
    </div>
    
    <?php if (isset($answer)) : ?>
        <div class="panel panel-default panel-info">
          <div class="panel-body panel-info">
            <?php echo htmlspecialchars($answer['msg'])?>
          </div>
        </div>
    <?php endif;?>
    
    <div class="btn-group" role="group" aria-label="...">
		<input type="submit" class="btn btn-default" name="Search" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Search');?>"/>
	</div>

</form>