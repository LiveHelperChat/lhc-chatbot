<?php if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['enabled'] == true && erLhcoreClassUser::instance()->hasAccessTo('lhlhcchatbot','use') == true) :
    $isEnabledPredictor = (int)erLhcoreClassModelUserSetting::getSetting('lhcchatbot_reply_predictor',1);
?>
<a href="<?php echo erLhcoreClassDesign::baseurl('user/setsetting')?>/lhcchatbot_reply_predictor/<?php echo $isEnabledPredictor == 1 ? 0 : 1?>" class="dropdown-item ps-2 csfr-required" >
    <i class="material-icons" title="Turn of/off experimental auto complete">science</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler', 'Turn reply predictor'); ?> <?php if ($isEnabledPredictor == 0) : ?><b><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler', 'on'); ?></b><?php else : ?><b><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler', 'off'); ?></b><?php endif; ?>
</a>
<?php endif; ?>