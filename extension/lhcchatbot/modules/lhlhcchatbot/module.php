<?php

$Module = array( "name" => "LHCChatBot",
				 'variable_params' => true );

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'functions' => array('use')
);

$ViewList['test'] = array(
    'params' => array(),
    'functions' => array('use')
);

$ViewList['suggest'] = array(
    'params' => array(),
    'uparams' => array('id'),
    'functions' => array('use'),
    'multiple_arguments' => array ( 'id' )
);

$ViewList['list'] = array(
    'params' => array(),
    'functions' => array('use')
);

$ViewList['new'] = array(
    'params' => array(),
    'functions' => array('use')
);

$ViewList['edit'] = array(
    'params' => array('id'),
    'functions' => array('use')
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use')
);

$FunctionList['use'] = array('explain' => 'Allow to list questions and answers');
