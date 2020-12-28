<div role="tabpanel" class="tab-pane" id="lhcchatbot">
     <p><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('department/edit','Choose context to search')?></p>
     
     <div class="form-group">
     <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Context');?></label>
        <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
    			'input_name'     => 'context_id[]',
    			'display_name'   => 'name',
                'multiple'       => true,
                'css_class'      => 'form-control',
    			'selected_id'    => erLhcoreClassExtensionLhcchatbot::getDepartmentContext($departament),
    			'list_function'  => 'erLhcoreClassModelLHCChatBotContext::getList',
    			'list_function_params'  => array_merge(array('limit' => '1000000')),
    	)); ?>
     </div>

     <label><input type="checkbox" name="enable_autocompleter" <?php isset($departament->bot_configuration_array['lhcacmplt']) && $departament->bot_configuration_array['lhcacmplt'] == 1 ? print 'checked="checked"' : '' ?> value="1"> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Enable auto completer')?></label>

</div>