<?php

$fieldsSearch = array();

$fieldsSearch['sort'] = array (
    'type' => 'text',
    'trans' => 'Sort',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => false,
    'validation_definition' => new ezcInputFormDefinitionElement (
        ezcInputFormDefinitionElement::OPTIONAL, 'string'
    )
);

$fieldsSearch['context_id'] = array (
    'type' => 'text',
    'trans' => 'Username',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => 'like',
    'filter_table_field' => 'context_id',
    'validation_definition' => new ezcInputFormDefinitionElement (
        ezcInputFormDefinitionElement::OPTIONAL, 'int' , array('min_range' => 1)
    )
);

$fieldSortAttr = array (
    'field'      => 'sort',
    'default'    => 'newfirst',
    'sort_column' => 'id',
    'options'    => array(
        'newfirst' => array('sort_column' => '`id` DESC'),
        'oldfirst' => array('sort_column' => '`id` ASC'),
        'wasused' => array('sort_column' => '`counter` DESC'),
    )
);

return array(
    'filterAttributes' => $fieldsSearch,
    'sortAttributes'   => $fieldSortAttr
);