<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Reply Predictions')?></h1>
<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/list')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Question')?></a></li>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhlhcchatbot','manage_invalid')) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/invalid')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Invalid questions')?></a></li>
    <?php endif; ?>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhlhcchatbot','manage_context')) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/listcontext')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Context')?></a></li>
    <?php endif; ?>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhlhcchatbot','use_test')) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/test')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Test console')?></a></li>
    <?php endif; ?>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhlhcchatbot','manage_completer')) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/autocompleter')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Auto completer')?></a></li>
    <?php endif; ?>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhrasaaitraining','use_admin')) : ?>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/list')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Rasa AI Intent list')?></a></li>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/listexample')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Rasa AI Intent examples list')?></a></li>
    <?php endif; ?>

    <?php if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['elastic_enabled'] == true) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/elastic')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Elastic search console')?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/list')?>/(hidden)/0"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Proposed Questions')?></a></li>
    <?php endif; ?>
</ul>

<?php if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['elastic_enabled'] == true) : ?>
<div class="row">
    <div class="col-md-12">
        <div id="status-elastic"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearchbot/admin','Comparing current elastic structure, please wait...')?></div>
    </div>
</div>
<script>
    function updateElasticStructure() {
        $('#elastic-status-checked').hide();
        $('#elastic-status-updating').show();
        $.postJSON('<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/updateelastic')?>/(action)/updateelastic',function(data){
            $('#status-elastic').html(data.result);
        });
    };

    function crateElasticIndexs() {
        $('#elastic-status-checked').hide();
        $('#elastic-status-updating').show();
        $.postJSON('<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/updateelastic')?>/(action)/createelasticindex',function(data){
            $('#status-elastic').html(data.result);
        });
    };

    (function() {
        $.postJSON('<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/updateelastic')?>/(action)/statuselastic', function(data){
            $('#status-elastic').html(data.result);
        });
    })();
</script>
<?php endif; ?>