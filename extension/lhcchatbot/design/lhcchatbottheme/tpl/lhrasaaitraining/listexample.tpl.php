<?php if ($ajax == false) : ?>
<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Rasa AI Intent Training');?></h1>

    <?php include(erLhcoreClassDesign::designtpl('lhrasaaitraining/search_example_panel.tpl.php')); ?>


    <form action="<?php echo $input->form_action,$inputAppend?>" method="post" ng-non-bindable>
        <div id="examples-table-rasa">
<?php endif; ?>

            <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>
            <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%">
                <thead>
                <tr>
                    <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Intent');?></th>
                    <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Example');?></th>
                    <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Comment');?></th>
                    <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Verified');?></th>
                    <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Active');?></th>
                    <th width="1%"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td>
                            <button title="Edit intent" data-intent-id="<?php echo $item->intent_id?>" data-id="<?php echo $item->id?>" type="button" id="intent-edit-btn-<?php echo $item->id?>" class="float-left edit-intent btn btn-xs btn-link"><span class="material-icons">&#xE254;</span></button>
                            <span class="float-left" id="intent-list-<?php echo $item->id?>">
                                <a href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/editexample')?>/<?php echo $item->id?>">
                                    <?php if ($item->intent instanceof erLhcoreClassModelLHCChatBotRasaIntent) : ?>
                                        <?php echo htmlspecialchars($item->intent->name . ' | ' . $item->intent); ?>
                                    <?php else : ?>
                                        <?php echo $item->id;?>
                                    <?php endif; ?>
                                </a>
                            </span>
                        </td>
                        <td title="<?php echo htmlspecialchars($item->example)?>"><?php echo erLhcoreClassDesign::shrt($item->example, 100, '...', 30)?></td>
                        <td title="<?php echo htmlspecialchars($item->comment)?>"><?php echo  erLhcoreClassDesign::shrt($item->comment, 100, '...', 30)?></td>
                        <td>
                            <label><input data-id="<?php echo $item->id?>" id="verified-<?php echo $item->id?>" class="verify-check" type="checkbox" <?php $item->verified == 1 ? print 'checked' : ''?> /></label>
                        </td>
                        <td>
                            <label><input data-id="<?php echo $item->id?>" id="active-<?php echo $item->id?>" class="active-check" type="checkbox" <?php $item->active == 1 ? print 'checked' : ''?> /></label>
                        </td>
                        <td nowrap>
                            <div class="btn-group" role="group" aria-label="..." style="width:60px;">
                                <a class="btn btn-secondary btn-xs" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/editexample')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE254;</i></a>
                                <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/deleteexample')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE872;</i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (isset($pages)) : ?>
                <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
            <?php endif;?>
        </div>
        <?php if ($ajax == false) : ?>
        <script>

        $('#examples-table-rasa').on('change','.intent-item-edit', function() {
            var exampleId = $(this).attr('data-id');
            $.post('<?php echo $input->form_action,$inputAppend?>/?setintent=1', {id: exampleId, intent_id: $(this).val()}, function(data) {
                $('#intent-edit-btn-'+exampleId).addClass('text-success');
            });
        });

        $('#examples-table-rasa').on('click','.edit-intent', function() {
            var exampleId = $(this).attr('data-id');
            var intentId = $(this).attr('data-intent-id');
            $.post('<?php echo $input->form_action,$inputAppend?>/?intentlist=1', {id: exampleId, intent_id: intentId}, function(data) {
                $('#intent-list-'+exampleId).html(data);
            });
        });

        $('#examples-table-rasa').on('click','.verify-check,.active-check', function() {
            $.post('<?php echo $input->form_action,$inputAppend?>/?ajax=1',{id:
                    $(this).attr('data-id'),
                    'change_data': true,
                    'verified': $('#verified-'+$(this).attr('data-id')).is(':checked'),
                    'active': $('#active-'+$(this).attr('data-id')).is(':checked')
            },function(data) {
                $('#examples-table-rasa').html(data);
            });
        });
        </script>

        <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>



    </form>


<div><a class="btn btn-secondary btn-sm" href="<?php echo erLhcoreClassDesign::baseurl('rasaaitraining/newexample')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','New');?></a></div>
<?php endif; ?>