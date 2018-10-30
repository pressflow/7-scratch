<?php

/**
 * Get the ESI (or other similar) tag to render the requested content.
 *
 * @param $mode
 *   Values such as DRUPAL_NO_CACHE and DRUPAL_CACHE_PER_ROLE.
 * @param $function
 *   Function to call on the ESI.
 * @param $arguments...
 *   Any number of arguments to pass to the specified function.
 */
function edge_get_tag() {
  global $theme, $user;

  $arguments = func_get_args();

  $mode = array_shift($arguments);
  $function = array_shift($arguments);

  // In the case of a disabled or custom cache, call the function directly.
  if ($mode == DRUPAL_NO_CACHE || $mode == DRUPAL_CACHE_CUSTOM) {
    return call_user_func_array($function, $arguments);
  }

  $request = array(
    'f' => $function,
    'a' => $arguments,
    'e' => array(),
  );

  $request['e']['t'] = $theme;

  if ($mode & DRUPAL_CACHE_PER_USER) {
    $request['e']['u'] = $user->uid;
  }
  elseif ($mode & DRUPAL_CACHE_PER_ROLE) {
    $request['e']['r'] = array_keys($user->roles);
  }

  if ($mode & DRUPAL_CACHE_PER_PAGE) {
    $request['e']['p'] = $_GET['q'];
  }

  $encoded_request = serialize($request);

  // TODO: Add crypto hash.

  // Return the appropriate ESI tag.
  $tag = '<esi:include src="' . base_path() . 'edge.php?r=' . rawurlencode(base64_encode($encoded_request)) . '" />';
  return $tag;
}

function edge_test_function($arg) {
  return 'X' . $arg . 'Y';
}
