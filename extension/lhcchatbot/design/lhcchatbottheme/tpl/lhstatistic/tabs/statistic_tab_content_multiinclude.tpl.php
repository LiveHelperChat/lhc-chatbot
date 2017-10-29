<?php if ($tab == 'botusage' && class_exists('erLhcoreClassExtensionElasticsearch') ) : ?>
<div role="tabpanel" class="tab-pane active">
  <?php include(erLhcoreClassDesign::designtpl('lhstatistic/tabs/botusage.tpl.php'));?>
</div>
<?php endif;?>