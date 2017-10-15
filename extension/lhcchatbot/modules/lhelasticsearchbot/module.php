<?php

$Module = array( "name" => "ChatBot Elastic Search",
				 'variable_params' => true );

$ViewList = array();

$ViewList['options'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure')
);

$ViewList['elastic'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure')
);

$ViewList['list'] = array(
    'params' => array(),
    'uparams' => array('sort','confirmed'),
    'functions' => array('configure')
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('configure')
);

$ViewList['updateelastic'] = array(
    'params' => array(),
    'uparams' => array('action'),
    'functions' => array('configure')
);

$ViewList['viewquestion'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('configure')
);

$FunctionList['configure'] = array('explain' => 'Allow operator to configure ChatBot Elastic Search module');