<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_lhcchatbot_invalid";
$def->class = "erLhcoreClassModelLHCChatBotInvalid";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['question'] = new ezcPersistentObjectProperty();
$def->properties['question']->columnName   = 'question';
$def->properties['question']->propertyName = 'question';
$def->properties['question']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['answer'] = new ezcPersistentObjectProperty();
$def->properties['answer']->columnName   = 'answer';
$def->properties['answer']->propertyName = 'answer';
$def->properties['answer']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['counter'] = new ezcPersistentObjectProperty();
$def->properties['counter']->columnName   = 'counter';
$def->properties['counter']->propertyName = 'counter';
$def->properties['counter']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;