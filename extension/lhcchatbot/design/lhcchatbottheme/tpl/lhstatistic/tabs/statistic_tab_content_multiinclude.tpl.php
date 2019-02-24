<?php if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['elastic_enabled'] == true && $tab == 'botusage' && class_exists('erLhcoreClassExtensionElasticsearch') ) : ?>
<div role="tabpanel" class="tab-pane active">
  <?php include(erLhcoreClassDesign::designtpl('lhstatistic/tabs/botusage.tpl.php'));?>
</div>
<?php endif;?>