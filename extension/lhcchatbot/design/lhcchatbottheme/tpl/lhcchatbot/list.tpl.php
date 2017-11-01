<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Questions and answers');?></h1>

<?php include(erLhcoreClassDesign::designtpl('lhcchatbot/parts/filter.tpl.php')); ?>

<?php if ($pages->items_total > 0) { ?>

<form action="<?php echo $input->form_action,$inputAppend?>" method="post">

<?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

<table cellpadding="0" cellspacing="0" class="table" width="100%">
    <thead>
        <tr>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Question');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Answer');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Context');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Was used');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Confirmed');?></th>
            <th width="1%"></th>
        </tr>
    </thead>
    <?php foreach ($items as $item) : ?>
    <tr>
        <td><?php echo htmlspecialchars($item->question)?></td>
        <td><?php echo htmlspecialchars($item->answer)?></td>
        <td><?php echo htmlspecialchars($item->context)?></td>
        <td><?php echo htmlspecialchars($item->was_used)?></td>
        <td><?php echo htmlspecialchars($item->confirmed)?></td>
        <td nowrap>
          <div class="btn-group" role="group" aria-label="..." style="width:60px;">
            <a class="btn btn-default btn-xs" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/edit')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE254;</i></a>
            <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/delete')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE872;</i></a>
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

<div><a class="btn btn-default" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/new')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','New');?></a></div>