<?php

// Get Drupal ready to handle the request.
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// Verify that the request is from a listed proxy.
//$proxies = variable_get('reverse_proxy_addresses', array());
//if (!in_array($_SERVER['REMOTE_ADDR'], $proxies)) {
//  return;
//}

// Load the request data.
$request = unserialize(base64_decode($_GET['r']));

// Re-establish the environment.
$GLOBALS['theme'] = $request['e']['t']; // Does this work?

if (isset($request['e']['u'])) {
  $GLOBALS['user'] = user_load($request['e']['u']);
}

if (isset($request['e']['r'])) {
  $GLOBALS['user']->roles = $request['e']['r'];
}

if (isset($request['p'])) {
  $_GET['q'] = $request['e']['p'];
}

// Call the requested function.
$response = call_user_func_array($request['f'], $request['a']);

echo $response;
