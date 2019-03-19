<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Edit Report');?></h1>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<form action="<?php echo erLhcoreClassDesign::baseurl('lhcchatbot/editreport')?>/<?php echo $report->id?>" method="post">

    <p><b><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Question');?></b> - <?php echo htmlspecialchars($report->question)?></p>
    <p><b><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Answer');?></b> - <?php echo htmlspecialchars($report->answer)?></p>

    <?php include(erLhcoreClassDesign::designtpl('lhcchatbot/question/form.tpl.php'));?>

    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" class="btn btn-secondary" name="Save_action" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save');?>"/>
        <input type="submit" class="btn btn-secondary" name="Cancel_action" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Cancel');?>"/>
    </div>

</form>
