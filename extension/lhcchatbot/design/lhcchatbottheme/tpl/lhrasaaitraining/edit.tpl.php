

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<div class="row">
    <div class="col-8">
        <h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('rasatraining/form','Edit');?></h1>

        <form action="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/edit')?>/<?php echo $item->id?>" method="post" ng-non-bindable>

            <?php include(erLhcoreClassDesign::designtpl('lhrasaaitraining/form.tpl.php'));?>

            <div class="btn-group" role="group" aria-label="...">
                <input type="submit" class="btn btn-secondary btn-sm" name="Save_action" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save');?>"/>
                <input type="submit" class="btn btn-secondary btn-sm" name="Cancel_action" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Cancel');?>"/>
            </div>

        </form>
    </div>
    <div class="col-4">
        <h5 class="text-muted">Used in</h5>
        <?php $db = ezcDbInstance::get();
        foreach (erLhcoreClassModelGenericBotTriggerEvent::getList(['customfilter' => [
                'pattern LIKE (' . $db->quote('%' . $item->intent . '%') .') OR pattern_exc LIKE (' . $db->quote('%' . $item->intent . '%') .')'
        ]]) as $item) : ?>
            <a href="<?php is_object($item->trigger) ? print erLhcoreClassDesign::baseurl('genericbot/bot') . '/' . $item->trigger->bot_id . '#/trigger-'. $item->trigger_id: '#' ?>" class="btn btn-xs btn-info"><?php
                if (is_object($item->trigger)) {
                     echo htmlspecialchars($item->trigger). ' | ' . htmlspecialchars(erLhcoreClassModelGenericBotBot::fetch($item->trigger->bot_id));
                } else {
                    echo 'Trigger not found!';
                }
                ?></a>
        <?php endforeach; ?>
    </div>
</div>


