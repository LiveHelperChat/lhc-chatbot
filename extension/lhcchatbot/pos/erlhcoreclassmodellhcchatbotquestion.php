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

$def->properties['hash'] = new ezcPersistentObjectProperty();
$def->properties['hash']->columnName   = 'hash';
$def->properties['hash']->propertyName = 'hash';
$def->properties['hash']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['rasa_intent_id'] = new ezcPersistentObjectProperty();
$def->properties['rasa_intent_id']->columnName   = 'rasa_intent_id';
$def->properties['rasa_intent_id']->propertyName = 'rasa_intent_id';
$def->properties['rasa_intent_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['answer'] = new ezcPersistentObjectProperty();
$def->properties['answer']->columnName   = 'answer';
$def->properties['answer']->propertyName = 'answer';
$def->properties['answer']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['context_id'] = new ezcPersistentObjectProperty();
$def->properties['context_id']->columnName   = 'context_id';
$def->properties['context_id']->propertyName = 'context_id';
$def->properties['context_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['canned_id'] = new ezcPersistentObjectProperty();
$def->properties['canned_id']->columnName   = 'canned_id';
$def->properties['canned_id']->propertyName = 'canned_id';
$def->properties['canned_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['was_used'] = new ezcPersistentObjectProperty();
$def->properties['was_used']->columnName   = 'was_used';
$def->properties['was_used']->propertyName = 'was_used';
$def->properties['was_used']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['confirmed'] = new ezcPersistentObjectProperty();
$def->properties['confirmed']->columnName   = 'confirmed';
$def->properties['confirmed']->propertyName = 'confirmed';
$def->properties['confirmed']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['chat_id'] = new ezcPersistentObjectProperty();
$def->properties['chat_id']->columnName   = 'chat_id';
$def->properties['chat_id']->propertyName = 'chat_id';
$def->properties['chat_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;