<?php

$Module = array("name" => "Rasa training");

$ViewList = array();

$ViewList['list'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['listexample'] = array(
    'params' => array(),
    'uparams' => array('active','verified','intent_id'),
    'functions' => array('use_admin')
);

$ViewList['new'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['newexample'] = array(
    'params' => array(),
    'functions' => array('use_admin')
);

$ViewList['edit'] = array(
    'params' => array('id'),
    'functions' => array('use_admin')
);

$ViewList['editexample'] = array(
    'params' => array('id'),
    'functions' => array('use_admin')
);

$ViewList['deleteexample'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_admin')
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_admin')
);

$ViewList['download'] = array(
    'params' => array('id')
);

$FunctionList['use_admin'] = array('explain' => 'Allow to use Rasa AI training module');
