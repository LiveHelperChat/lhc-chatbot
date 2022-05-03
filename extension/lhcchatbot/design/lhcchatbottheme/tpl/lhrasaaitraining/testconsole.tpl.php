<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Rasa AI test console')?></h1>
<p><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Make sure you have entered some questions')?></p>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/form','Rasa AI Server');?></label>
        <input list="rasa-server-list" id="id_rasa_ai_server" placeholder="E.g http://127.0.0.1:5058/model" type="text" name="rasa_ai_server" class="form-control form-control-sm" value="<?php echo htmlspecialchars($input->rasa_ai_server)?>" />
    </div>

    <datalist id="rasa-server-list">
        <?php include(erLhcoreClassDesign::designtpl('lhrasaaitraining/rasa_ai_server_multiinclude.tpl.php'));?>
    </datalist>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/form','Question');?></label>
        <textarea id="id_question_content" name="question" class="form-control form-control-sm" rows="10"><?php echo htmlspecialchars($input->question)?></textarea>
    </div>

    <?php if (isset($answer)) : ?>
        <div class="alert alert-info">
            <?php if (is_array($answer)) : ?>
                <i class="material-icons">search</i><br/>
                <pre><?php echo json_encode($answer,JSON_PRETTY_PRINT);?></pre>
            <?php else : ?>
                <?php echo htmlspecialchars($answer)?>
            <?php endif; ?>
        </div>
    <?php endif;?>

    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" class="btn btn-sm btn-secondary" name="TestAction" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Test');?>"/>
    </div>

    <script>
        $('#id_rasa_ai_server').on('keyup keypress change', function() {
            if (typeof templateRequestAI !== 'undefined' && templateRequestAI[$(this).val()] !== undefined) {
                $('#id_question_content').val(templateRequestAI[$(this).val()]);
            }
        });
    </script>

</form>