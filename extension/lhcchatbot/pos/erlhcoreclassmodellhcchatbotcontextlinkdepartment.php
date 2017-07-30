<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_lhcchatbot_context_link_department";
$def->class = "erLhcoreClassModelLHCChatBotContextLinkDepartment";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['context_id'] = new ezcPersistentObjectProperty();
$def->properties['context_id']->columnName   = 'context_id';
$def->properties['context_id']->propertyName = 'context_id';
$def->properties['context_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['department_id'] = new ezcPersistentObjectProperty();
$def->properties['department_id']->columnName   = 'department_id';
$def->properties['department_id']->propertyName = 'department_id';
$def->properties['department_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;