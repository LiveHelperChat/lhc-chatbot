<h1 class="attr-header">Elastic Search Options</h1>

<form action="" method="post">

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>
    
    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/onlineusers','Settings updated'); ?>
    	<?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <div class="form-group">
        <label><input type="checkbox" value="on" name="use_es_statistic" <?php isset($es_options['use_es_statistic']) && ($es_options['use_es_statistic'] == true) ? print 'checked="checked"' : ''?> /> Use Elastic Search Statistic</label><br/>
    </div>
    
    <div class="form-group">
        <label>Last indexed message Id</label>
        <input type="text" class="form-control" name="last_index_msg_id" value="<?php isset($es_options['last_index_msg_id']) ? print $es_options['last_index_msg_id'] : ''?>" />
    </div>
        
    <input type="submit" class="btn btn-default" name="StoreOptions" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save'); ?>" />

</form>
