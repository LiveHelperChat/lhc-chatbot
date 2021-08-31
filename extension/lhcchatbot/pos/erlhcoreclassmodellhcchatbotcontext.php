<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_lhcchatbot_context";
$def->class = "erLhcoreClassModelLHCChatBotContext";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['host'] = new ezcPersistentObjectProperty();
$def->properties['host']->columnName   = 'host';
$def->properties['host']->propertyName = 'host';
$def->properties['host']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['meili'] = new ezcPersistentObjectProperty();
$def->properties['meili']->columnName   = 'meili';
$def->properties['meili']->propertyName = 'meili';
$def->properties['meili']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;