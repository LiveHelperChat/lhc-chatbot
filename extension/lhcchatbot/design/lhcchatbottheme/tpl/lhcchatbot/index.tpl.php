<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','ChatBot')?></h1>
<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/list')?>/(confirmed)/1"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Question')?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/listcontext')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Context')?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/test')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Test console')?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/elastic')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Elastic search console')?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/list')?>/(hidden)/0"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Proposed Questions')?></a></li>
</ul>

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