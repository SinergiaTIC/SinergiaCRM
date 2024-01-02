<?php
/*
 * Requerido a partir de la versión 7.7
 * Ver https://developer.sugarcrm.com/2015/12/01/csrf-tokens-in-sugar-7-7
 *     https://developer.sugarcrm.com/2016/11/15/security-changes-coming-in-sugar-7-8
 *
 * Basado en include\SugarSmarty\plugins\function.sugar_csrf_form_token.php
 * La forma de usar la variable original de Sugar en un form en Smarty es la siguiente (incluir en los tpl) ...
 *    {sugar_csrf_form_token}
 * o
 *    <input type="hidden" name="csrf_token" id="csrf_token" value="{sugar_csrf_form_token raw=1}" />
 */

use Sugarcrm\Sugarcrm\Security\Csrf\CsrfAuthenticator;

function get_sugar_csrf_form_token($raw){
   $csrf = CsrfAuthenticator::getInstance();

   if (!empty($raw)) {
      return $csrf->getFormToken();
   }

   return sprintf(
      '<input type="hidden" name="%s" id="%s" value="%s" />',
      $csrf::FORM_TOKEN_FIELD,
      $csrf::FORM_TOKEN_FIELD,
      $csrf->getFormToken()
   );
}


function get_sugar_csrf_form_token_id(){
   return CsrfAuthenticator::FORM_TOKEN_FIELD;
}
