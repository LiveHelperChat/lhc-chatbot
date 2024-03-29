<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_lhcchatbot_rasa_intent";
$def->class = "erLhcoreClassModelLHCChatBotRasaIntent";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['intent'] = new ezcPersistentObjectProperty();
$def->properties['intent']->columnName   = 'intent';
$def->properties['intent']->propertyName = 'intent';
$def->properties['intent']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['context_id'] = new ezcPersistentObjectProperty();
$def->properties['context_id']->columnName   = 'context_id';
$def->properties['context_id']->propertyName = 'context_id';
$def->properties['context_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['active'] = new ezcPersistentObjectProperty();
$def->properties['active']->columnName   = 'active';
$def->properties['active']->propertyName = 'active';
$def->properties['active']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['test_samples'] = new ezcPersistentObjectProperty();
$def->properties['test_samples']->columnName   = 'test_samples';
$def->properties['test_samples']->propertyName = 'test_samples';
$def->properties['test_samples']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['use_counter'] = new ezcPersistentObjectProperty();
$def->properties['use_counter']->columnName   = 'use_counter';
$def->properties['use_counter']->propertyName = 'use_counter';
$def->properties['use_counter']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def;