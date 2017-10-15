<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearchbot/admin','Elastic search bot')?></h1>

<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('elasticsearch/elastic')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Elastic Search console')?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('elasticsearch/options')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Elastic Search Options')?></a></li>
</ul>

<hr>

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