<?php

$Module = array("name" => "Reply Predictions",
                'variable_params' => true );

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['test'] = array(
    'params' => array(),
    'functions' => array('use_test')
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
    'uparams' => array('context_id','sort','confirmed','keyword','keyword_answer'),
    'functions' => array('use_admin')
);

$ViewList['listcontext'] = array(
    'params' => array(),
    'functions' => array('manage_context')
);

$ViewList['newcontext'] = array(
    'params' => array(),
    'functions' => array('manage_context')
);

$ViewList['editcontext'] = array(
    'params' => array('id'),
    'functions' => array('manage_context')
);

$ViewList['deletecontext'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('manage_context')
);

$ViewList['new'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['edit'] = array(
    'params' => array('id'),
    'functions' => array('use_admin')
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_admin')
);

$ViewList['deletereport'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('manage_invalid')
);

$ViewList['deleteall'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('manage_invalid')
);

$ViewList['invalid'] = array(
    'params' => array(),
    'uparams' => array('context_id','sort'),
    'functions' => array('manage_invalid')
);

$ViewList['editreport'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('manage_invalid')
);

$ViewList['autocompleter'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('manage_completer')
);

$FunctionList['use'] = array('explain' => 'Allow to list questions and answers');
$FunctionList['use_admin'] = array('explain' => 'Allow to manager bot');
$FunctionList['use_test'] = array('explain' => 'Allow operator to use test environment');
$FunctionList['manage_context'] = array('explain' => 'Allow operator to manage context');
$FunctionList['manage_invalid'] = array('explain' => 'Allow operator to manage invalid suggestions list');
$FunctionList['live_teach'] = array('explain' => 'Suggested combination by operator will go live instantly');
$FunctionList['manage_completer'] = array('explain' => 'Allow operator to change auto completer settings');
