<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Invalid questions/answers');?></h1>

<?php if ($pages->items_total > 0) { ?>

    <form action="<?php echo $input->form_action,$inputAppend?>" method="post">

        <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

        <table cellpadding="0" cellspacing="0" class="table" width="100%">
            <thead>
            <tr>
                <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Question');?></th>
                <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Answer');?></th>
                <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Counter');?></th>
                <th width="20%"></th>
            </tr>
            </thead>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <td>
                        <?php if ($item->chat_id > 0) : ?>
                            <a href="#" title="<?php echo $item->chat_id?>" onclick="lhc.previewChat(<?php echo $item->chat_id?>)"><i class="material-icons">chat</i></a>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($item->question)?>
                    </td>
                    <td><?php echo htmlspecialchars($item->answer)?></td>
                    <td><?php echo htmlspecialchars($item->counter)?></td>
                    <td nowrap>
                        <div class="btn-group" role="group" aria-label="..." style="width:60px;">
                            <?php /*<a class="btn btn-info btn-xs csfr-required" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/deletereport')?>/<?php echo $item->id?>" title="Edit training"><i class="material-icons mr-0">&#xE254;</i>Edit</a>*/ ?>
                            <a class="btn btn-warning btn-xs csfr-required" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/deletereport')?>/<?php echo $item->id?>" title="Only report will be deleted"><i class="material-icons mr-0">&#xE254;</i>Ignore</a>
                            <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" title="Report and related bot training will be deleted" href="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/deleteall')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE872;</i>Delete</a>
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