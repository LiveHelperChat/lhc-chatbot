<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_lhcchatbot_rasa_example";
$def->class = "erLhcoreClassModelLHCChatBotRasaExample";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['intent_id'] = new ezcPersistentObjectProperty();
$def->properties['intent_id']->columnName   = 'intent_id';
$def->properties['intent_id']->propertyName = 'intent_id';
$def->properties['intent_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['example'] = new ezcPersistentObjectProperty();
$def->properties['example']->columnName   = 'example';
$def->properties['example']->propertyName = 'example';
$def->properties['example']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['hash'] = new ezcPersistentObjectProperty();
$def->properties['hash']->columnName   = 'hash';
$def->properties['hash']->propertyName = 'hash';
$def->properties['hash']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['verified'] = new ezcPersistentObjectProperty();
$def->properties['verified']->columnName   = 'verified';
$def->properties['verified']->propertyName = 'verified';
$def->properties['verified']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['active'] = new ezcPersistentObjectProperty();
$def->properties['active']->columnName   = 'active';
$def->properties['active']->propertyName = 'active';
$def->properties['active']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;