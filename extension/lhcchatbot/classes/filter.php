<?php

$fieldsSearch = array();

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

$fieldsSearch['confirmed'] = array (
    'type' => 'text',
    'trans' => 'Confirmed',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => 'filter',
    'filter_table_field' => 'confirmed',
    'validation_definition' => new ezcInputFormDefinitionElement (
        ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
    )
);

$fieldSortAttr = array (
    'field'      => 'sort',
    'default'    => 'newfirst',
    'sort_column' => 'id',
    'options'    => array(
        'newfirst' => array('sort_column' => 'id DESC'),
        'oldfirst' => array('sort_column' => 'id ASC'),
        'wasused' => array('sort_column' => 'was_used DESC'),
    )
);

return array(
    'filterAttributes' => $fieldsSearch,
    'sortAttributes'   => $fieldSortAttr
);