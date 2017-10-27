<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Test')?></h1>
<p><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Make sure you have entered some questions')?></p>

<form action="" method="get">

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/form','Question');?></label>
        <input type="text" class="form-control" name="question"  value="<?php isset($_GET['question']) ? print htmlspecialchars($_GET['question']) : ''?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/form','Context');?></label>
        <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
            'input_name'     => 'context_id',
            'display_name'   => 'name',
            'css_class'      => 'form-control',
            'selected_id'    =>  isset($_GET['context_id']) ? (int)$_GET['context_id'] : 0,
            'list_function'  => 'erLhcoreClassModelLHCChatBotContext::getList',
            'list_function_params'  => array_merge(array('limit' => '1000000')),
        )); ?>
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