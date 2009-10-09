<?php
// $Id$

/**
 * @file
 * Hooks provided by the Locale module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Allows modules to define their own text groups that can be translated.
 *
 * @param $op
 *   Type of operation. Currently, only supports 'groups'.
 */
function hook_locale($op = 'groups') {
  switch ($op) {
    case 'groups':
      return array('custom' => t('Custom'));
  }
}

/**
 * Perform alterations on language switcher links.
 *
 * A language switcher link may need to point to a different path or use a
 * translated link text before going through l(), which will just handle the
 * path aliases.
 *
 * @param $links
 *   Nested array of links keyed by language code.
 * @param $type
 *   The language type the links will switch.
 * @param $path
 *   The current path.
 */
function hook_language_switch_link_alter(array &$links, $type, $path) {
  global $language;

  if ($type == LANGUAGE_TYPE_CONTENT && isset($links[$language])) {
    foreach ($links[$language] as $link) {
      $link['attributes']['class'][] = 'active-language';
    }
  }
}

/**
 * Allow modules to define their own language types.
 *
 * @return
 *   An array of language type definitions. Each language type has an identifier
 *   key. The language type definition is an associative array that may contain
 *   the following key-value pairs:
 *   - "name": The human-readable language type identifier.
 *   - "description": A description of the language type.
 */
function hook_language_types_info() {
  return array(
    'custom_language_type' => array(
      'name' => t('Custom language'),
      'description' => t('A custom language type.'),
    ),
  );
}

/**
 * Perform alterations on language types.
 *
 * @param $language_types
 *   Array of language type definitions.
 */
function hook_language_types_info_alter(array &$language_types) {
  if (isset($language_types['custom_language_type'])) {
    $language_types['custom_language_type_custom']['description'] = t('A far better description.');
  }
}

/**
 * Allow modules to define their own language providers.
 *
 * @return
 *   An array of language provider definitions. Each language provider has an
 *   identifier key. The language provider definition is an associative array
 *   that may contain the following key-value pairs:
 *   - "types": An array of allowed language types. If a language provider does
 *     not specify which language types it should be used with, it will be
 *     available for all the configurable language types.
 *   - "callbacks": An array of functions that will be called to perform various
 *     tasks. Possible key-value pairs are:
 *     - "language": Required. The callback that will determine the language
 *       value.
 *     - "switcher": The callback that will determine the language switch links
 *       associated to the current language provider.
 *     - "url_rewrite": The callback that will provide URL rewriting.
 *   - "file": A file that will be included before the callback is invoked; this
 *     allows callback functions to be in separate files.
 *   - "weight": The default weight the language provider has.
 *   - "name": A human-readable identifier.
 *   - "description": A description of the language provider.
 *   - "config": An internal path pointing to the language provider
 *     configuration page.
 *   - "cache": The value Drupal's page cache should be set to for the current
 *     language provider to be invoked.
 */
function hook_language_negotiation_info() {
  return array(
    'custom_language_provider' => array(
      'callbacks' => array(
        'language' => 'custom_language_provider_callback',
        'switcher' => 'custom_language_switcher_callback',
        'url_rewrite' => 'custom_language_url_rewrite_callback',
      ),
      'file' => drupal_get_path('module', 'custom') . '/custom.module',
      'weight' => -4,
      'types' => array('custom_language_type'),
      'name' => t('Custom language provider'),
      'description' => t('This is a custom language provider.'),
      'cache' => CACHE_DISABLED,
    ),
  );
}

/**
 * Perform alterations on language providers.
 *
 * @param $language_providers
 *   Array of language provider definitions.
 */
function hook_language_negotiation_info_alter(array &$language_providers) {
  if (isset($language_providers['custom_language_provider'])) {
    $language_providers['custom_language_provider']['config'] = 'admin/config/regional/language/configure/custom-language-provider';
  }
}

/**
 * @} End of "addtogroup hooks".
 */
