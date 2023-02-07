<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Dashboard');?></h1>

<h5>Rasa last training status</h5>
<ul>
    <li>Data: <?php isset($rasa_status['date']) ? print date('Y-m-d H:i:s',$rasa_status['date']).', '. erLhcoreClassChat::formatSeconds(time()-$rasa_status['date']).' ago.' : print 'n/a' ?></li>
    <li>Outcome:
        <?php if (isset($rasa_status['outcome']) && $rasa_status['outcome'] === false) : ?>
        <span class="badge bg-danger">failed</span>
        <?php elseif (isset($rasa_status['outcome']) && $rasa_status['outcome'] === true) : ?>
        <span class="badge bg-success">success</span>
        <?php else : ?>
            <span class="badge bg-secondary">no data</span>
        <?php endif; ?>
    </li>
</ul>

<div class="row">
    <div class="col-6">
        <h5><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Intent with actions');?></h5>
        <?php $db = ezcDbInstance::get(); $intentsWithoutAction = []; foreach ($items as $itemIntent) : ?>
            <?php
            if ((erLhcoreClassModelGenericBotTriggerEvent::getCount(['customfilter' => [
                'pattern LIKE (' . $db->quote('%' . $itemIntent->intent . '%') .') OR pattern_exc LIKE (' . $db->quote('%' . $itemIntent->intent . '%') .')'
            ]])) > 0) : ?>
                <div class="btn-group m-1">
                    <a target="_blank" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/edit')?>/<?php echo $itemIntent->id?>" class="btn btn-xs btn-info"><?php echo htmlspecialchars($itemIntent->intent . '|' . $itemIntent->context)?></a>
                    <button type="button" class="btn btn-xs btn-secondary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-bs-toggle="dropdown" aria-expanded="false" data-reference="parent">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                        <a class="dropdown-item" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/edit')?>/<?php echo $itemIntent->id?>">Edit</a>
                        <div class="dropdown-divider"></div>
                        <?php $db = ezcDbInstance::get();
                        foreach (erLhcoreClassModelGenericBotTriggerEvent::getList(['customfilter' => [
                            'pattern LIKE (' . $db->quote('%' . $itemIntent->intent . '%') .') OR pattern_exc LIKE (' . $db->quote('%' . $itemIntent->intent . '%') .')'
                        ]]) as $item) : ?>
                        <a href="<?php is_object($item->trigger) ? print erLhcoreClassDesign::baseurl('genericbot/bot') . '/' . $item->trigger->bot_id . '#/trigger-'. $item->trigger_id: '#' ?>" class="dropdown-item"><?php
                            if (is_object($item->trigger)) {
                                echo htmlspecialchars($item->trigger). ' | ' . htmlspecialchars(erLhcoreClassModelGenericBotBot::fetch($item->trigger->bot_id));
                            }
                            ?></a>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php else : $intentsWithoutAction[] = $itemIntent;?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="col-6">
        <h5><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Intent without actions');?></h5>
        <?php foreach ($intentsWithoutAction as $intentWithoutAction) : ?>
            <div class="btn-group m-1" role="group" aria-label="Basic example">
                <a target="_blank" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/edit')?>/<?php echo $intentWithoutAction->id?>" class="btn btn-xs btn-info"><?php echo htmlspecialchars($intentWithoutAction->intent . '|' . $intentWithoutAction->context)?></a>
                <a href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/edit')?>/<?php echo $intentWithoutAction->id?>/(action)/addaction" class="btn btn-xs btn-secondary">Add action</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>