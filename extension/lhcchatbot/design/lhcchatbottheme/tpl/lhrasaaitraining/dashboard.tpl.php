<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Dashboard');?></h1>
<div class="row">
    <div class="col-6">
        <h5><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Intent with actions');?></h5>
        <?php $db = ezcDbInstance::get(); foreach ($items as $itemIntent) : ?>
            <?php
            $intentsWithoutAction = [];
            if ((erLhcoreClassModelGenericBotTriggerEvent::getCount(['customfilter' => [
                'pattern LIKE (' . $db->quote('%' . $itemIntent->intent . '%') .') OR pattern_exc LIKE (' . $db->quote('%' . $itemIntent->intent . '%') .')'
            ]])) > 0) : ?>
                <a target="_blank" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/edit')?>/<?php echo $itemIntent->id?>" class="btn btn-xs btn-info m-1"><?php echo htmlspecialchars($itemIntent->intent . '|' . $itemIntent->context)?></a>
            <?php else : $intentsWithoutAction[] = $itemIntent;?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="col-6">
        <h5><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Intent without actions');?></h5>
        <?php foreach ($intentsWithoutAction as $intentWithoutAction) : ?>
            <a target="_blank" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/edit')?>/<?php echo $itemIntent->id?>" class="btn btn-xs btn-info m-1"><?php echo htmlspecialchars($itemIntent->intent . '|' . $itemIntent->context)?></a>
        <?php endforeach; ?>
    </div>
</div>