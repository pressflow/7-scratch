<?php
// $Id$

/**
 * @file
 * PHP page for handling incoming XML-RPC requests from clients.
 */

/**
 * Root directory of Drupal installation.
 */
define('DRUPAL_ROOT', getcwd());

include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
include_once DRUPAL_ROOT . '/includes/xmlrpc.inc';
include_once DRUPAL_ROOT . '/includes/xmlrpcs.inc';

$services = module_invoke_all('xmlrpc');
drupal_alter('xmlrpc_alter', $services);
xmlrpc_server($services);
