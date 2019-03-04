<?php

$Module = array( "name" => "LHCChatBot",
				 'variable_params' => true );

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['test'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['suggest'] = array(
    'params' => array(),
    'uparams' => array('id','chat'),
    'functions' => array('use'),
    'multiple_arguments' => array ( 'id' )
);

$ViewList['suggestused'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use')
);

$ViewList['savecombination'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use')
);

$ViewList['suggestinvalid'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use')
);

$ViewList['list'] = array(
    'params' => array(),
    'uparams' => array('context_id','sort','confirmed'),
    'functions' => array('use_admin')
);

$ViewList['invalid'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use')
);

$ViewList['listcontext'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['new'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['newcontext'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['edit'] = array(
    'params' => array('id'),
    'functions' => array('use_admin')
);

$ViewList['editcontext'] = array(
    'params' => array('id'),
    'functions' => array('use_admin')
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_admin')
);

$ViewList['deletecontext'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_admin')
);

$ViewList['deletereport'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_admin')
);

$ViewList['deleteall'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_admin')
);

$FunctionList['use'] = array('explain' => 'Allow to list questions and answers');
$FunctionList['use_admin'] = array('explain' => 'Allow to manager bot');
