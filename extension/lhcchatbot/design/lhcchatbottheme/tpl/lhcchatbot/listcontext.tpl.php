<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Context');?></h1>

<?php if ($pages->items_total > 0) { ?>

<form action="<?php echo $input->form_action,$inputAppend?>" method="post" ng-non-bindable>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

<table cellpadding="0" cellspacing="0" class="table" width="100%">
    <thead>
        <tr>   
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Name');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Rasa training');?></th>
            <th width="1%"></th>
        </tr>
    </thead>
    <?php foreach ($items as $item) : ?>
    <tr>
        <td><?php echo htmlspecialchars($item->name)?></td>
        <td><a target="_blank" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/download')?>/<?php echo $item->id?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Download');?></a> </td>
        <td nowrap>
          <div class="btn-group" role="group" aria-label="..." style="width:60px;">
            <a class="btn btn-secondary btn-xs" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/editcontext')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE254;</i></a>
            <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/deletecontext')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE872;</i></a>
          </div>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<?php endif;?>

</form>

<?php } else { ?>
<br/>
<p><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/activechats','Empty...');?></p>
<?php } ?>

<div><a class="btn btn-secondary" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/newcontext')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','New');?></a></div>