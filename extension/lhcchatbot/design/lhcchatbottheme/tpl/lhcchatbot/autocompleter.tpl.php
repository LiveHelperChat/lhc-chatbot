<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Auto completer');?></h1>

<form action="" method="post">

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/onlineusers','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <div class="form-group">
        <label><input type="checkbox" value="on" name="enabled" <?php isset($lhcchatbot_options['enabled']) && ($lhcchatbot_options['enabled'] == true) ? print 'checked="checked"' : ''?> /> Enabled</label><br/>
    </div>

    <div class="form-group">
        <label>MeiliSearch address</label>
        <input type="text" class="form-control form-control-sm" name="msearch_host" placeholder="E.g /msearch/ http://localhost:7700/" value="<?php isset($lhcchatbot_options['msearch_host']) ? print htmlspecialchars($lhcchatbot_options['msearch_host']) : print ''?>" />
    </div>

    <div class="form-group">
        <label>Public key</label>
        <input type="text" class="form-control form-control-sm" name="public_key" value="<?php isset($lhcchatbot_options['public_key']) ? print htmlspecialchars($lhcchatbot_options['public_key']) : print ''?>" />
    </div>

    <input type="submit" class="btn btn-secondary" name="StoreOptions" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save'); ?>" />

</form>
