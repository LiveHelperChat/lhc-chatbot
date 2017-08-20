<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Proposed questions list')?></h1>

<?php include(erLhcoreClassDesign::designtpl('elasticsearchbot/parts/filter.tpl.php')); ?>

<?php if ($pages->items_total > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th width="98%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Question')?></th>
                <th width="1%" nowrap="nowrap"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/admin','Match count')?></th>
                <?php /*<th width="1%"></th>*/ ?>
            </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td><a href="#" onclick="lhc.previewChat(<?php echo $item->chat_id?>)"><i class="material-icons">info_outline</i></a> <a href="<?php echo erLhcoreClassDesign::baseurl('elasticsearchbot/viewquestion')?>/<?php echo $item->id?>"> <?php echo htmlspecialchars($item->question)?></a></td>
                <td><?php echo htmlspecialchars($item->match_count)?></td>
                <?php /*<td>
                    <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?php echo erLhcoreClassDesign::baseurl('elasticsearch/delete')?>/<?php echo $item->id?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Delete');?></a>
                </td>*/ ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>

<?php else: ?>
    <p><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/text','No records found')?></p>
<?php endif; ?>
