<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Rasa AI Intent Training');?></h1>

<?php include(erLhcoreClassDesign::designtpl('lhrasaaitraining/search_example_panel.tpl.php')); ?>

<?php if ($pages->items_total > 0) { ?>

    <form action="<?php echo $input->form_action,$inputAppend?>" method="post" ng-non-bindable>

        <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

        <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%">
            <thead>
            <tr>
                <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Intent');?></th>
                <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Example');?></th>
                <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Comment');?></th>
                <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Active');?></th>
                <th width="1%"></th>
            </tr>
            </thead>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <td>
                        <a href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/editexample')?>/<?php echo $item->id?>">
                            <?php if ($item->intent instanceof erLhcoreClassModelLHCChatBotRasaIntent) : ?>
                                <?php echo htmlspecialchars($item->intent->name . ' | ' . $item->intent); ?>
                            <?php else : ?>
                                <?php echo $item->id;?>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td><?php echo  erLhcoreClassDesign::shrt($item->example, 100, '...', 30)?></td>
                    <td><?php echo  erLhcoreClassDesign::shrt($item->comment, 100, '...', 30)?></td>
                    <td><?php echo htmlspecialchars($item->active)?></td>
                    <td nowrap>
                        <div class="btn-group" role="group" aria-label="..." style="width:60px;">
                            <a class="btn btn-secondary btn-xs" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/editexample')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE254;</i></a>
                            <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/deleteexample')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE872;</i></a>
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

<div><a class="btn btn-secondary btn-sm" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/newexample')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','New');?></a></div>