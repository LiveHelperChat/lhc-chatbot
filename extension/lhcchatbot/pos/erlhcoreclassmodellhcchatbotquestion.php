<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_lhcchatbot_question";
$def->class = "erLhcoreClassModelLHCChatBotQuestion";

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

$def->properties['context_id'] = new ezcPersistentObjectProperty();
$def->properties['context_id']->columnName   = 'context_id';
$def->properties['context_id']->propertyName = 'context_id';
$def->properties['context_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;