<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

// Sección de administración de SinergiaCRM
$mod_strings['LBL_SINERGIACRM_TAB_TITLE'] = 'SinergiaCRM';
$mod_strings['LBL_SINERGIACRM_TAB_DESCRIPTION'] = 'Opcións de administración de SinergiaCRM';

// Elementos de la sección de administración (títulos y descripciones cortas)
$mod_strings['LBL_STIC_VALIDATION_ACTIONS_LINK_TITLE'] = 'Accións de validación';
$mod_strings['LBL_STIC_VALIDATION_ACTIONS_DESCRIPTION'] = 'Xestiona as accións de validación e permite vinculalas ás tarefas programadas.';

$mod_strings['LBL_STIC_VALIDATION_RESULTS_LINK_TITLE'] = 'Resultados da validación';
$mod_strings['LBL_STIC_VALIDATION_RESULTS_DESCRIPTION'] = 'Xestiona e revisa os resultados das accións de validación.';

$mod_strings['LBL_STIC_CUSTOM_VIEWS_LINK_TITLE'] = 'Vistas personalizadas';
$mod_strings['LBL_STIC_CUSTOM_VIEWS_DESCRIPTION'] = 'Personalización condicional das vistas dos módulos.';

$mod_strings['LBL_STIC_MESSAGES_QUEUE_LINK_TITLE'] = 'Cola de mensaxes de teléfono';
$mod_strings['LBL_STIC_MESSAGES_QUEUE_DESCRIPTION'] = 'Xestión da cola de mensaxes de teléfono (SMS)';

$mod_strings['LBL_STIC_SETTINGS_LINK_TITLE'] = 'Configuración';
$mod_strings['LBL_STIC_SETTINGS_DESCRIPTION'] = 'Opcións de configuración de SinergiaCRM.';

$mod_strings['LBL_STIC_TEST_DATA_LINK_TITLE'] = 'Datos de proba';
$mod_strings['LBL_STIC_TEST_DATA_DESCRIPTION'] = 'Cargar ou eliminar datos de proba.';

$mod_strings['LBL_STIC_SINERGIADA_LINK_TITLE'] = 'Sinergia Data Analytics';
$mod_strings['LBL_STIC_SINERGIADA_DESCRIPTION'] = 'Reconstrúe a integración con Sinergia Data Analytics.';
$mod_strings['LBL_STIC_SINERGIADA_MAX_USERS_ERROR'] = 'Excedeuse o límite de usuarios non administradores en SinerxiaDA. Máximo permitido: <b>__max_users__</b>. Valor actual: <b>__enabled_users__</b>. Desactive os usuarios de máis e inténteo de novo.';

$mod_strings['LBL_STIC_MAIN_MENU_LINK_TITLE'] = 'Menú principal';
$mod_strings['LBL_STIC_MAIN_MENU_DESCRIPTION'] = 'Configuración da estrutura e do contido do menú';

// Datos de prueba
$mod_strings['LBL_STIC_TEST_DATA_NOTICE'] = '<strong>Importante:</strong> Os rexistros de proba cargados nos diferentes módulos non deben usarse para almacenar datos reais, xa que poden ser eliminados no futuro.';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_LINK_TITLE'] = 'Cargar o conxunto de datos de proba';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_DESCRIPTION'] = 'Cargar un conxunto de datos de proba para facilitar a aprendizaxe do uso de SinergiaCRM. Estes datos poderán ser eliminados a vontade en cualquera momento posterior.';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_SUCCESS'] = 'Insertáronse os datos de proba correctamente.';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_ERROR'] = 'Producíronse erros ao insertar os datos de proba. Revise o <a target="_blank" href="index.php?action=LogView&module=Configurator&doaction=all&filter=action_insertSticData">log</a>.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_LINK_TITLE'] = 'Eliminar o conxunto de datos de proba';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_DESCRIPTION'] = 'Eliminar o conxunto de datos de proba previamente cargado.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_SUCCESS'] = 'Elimináronse os datos de proba correctamente.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_ERROR'] = 'Producíronse erros ao eliminar os datos de proba. Revise o <a target="_blank" href="index.php?action=LogView&module=Configurator&doaction=all&filter=action_removeSticData">log</a>.';

// SinergiaDA
$mod_strings['LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE'] = 'Reconstruir agora';
$mod_strings['LBL_STIC_RUN_SDA_ACTIONS_DESCRIPTION'] = 'Reconstrúe e repara as vistas e os elementos necesarios para a integración con Sinergia Data Analytics. Engade novos campos se os hai.';
$mod_strings['LBL_STIC_RUN_SDA_DEBUG_CHECK_LABEL'] = 'Activar modo de depuración';
$mod_strings['LBL_STIC_GO_TO_SDA_LINK_TITLE'] = 'Ir a Sinergia Data Analytics';
$mod_strings['LBL_STIC_RUN_SDA_SUCCESS_MSG'] = 'A reconstrución de Sinergia Data Analytics completouse con éxito.';
$mod_strings['LBL_STIC_RUN_SDA_ERROR_MSG'] = 'Durante a reconstrución de Sinergia Data Analytics atopáronse os seguintes erros. Contacte co soporte técnico de SinergiaTIC se o considera necesario.';

// Menú principal avanzado
$mod_strings['LBL_STIC_MENU_CONFIGURE_TITLE'] = 'Configuración do menú principal';
$mod_strings['LBL_STIC_MENU_ENABLED_NOT_INCLUDED'] = 'Módulos habilitados non incluídos no menú';
$mod_strings['LBL_STIC_MENU_ENABLED_INCLUDED'] = 'Configuración do menú';
$mod_strings['LBL_STIC_MENU_SAVE'] = 'Gardar e aplicar';
$mod_strings['LBL_STIC_MENU_RESTORE'] = 'Restaurar';
$mod_strings['LBL_STIC_MENU_RESTORE_CONFIRM'] = 'Restaurar o menú predeterminado de SinergiaCRM?';
$mod_strings['LBL_STIC_MENU_INFO'] = 'O menú principal contén dous tipos de elementos: por unha banda, accesos directos aos distintos módulos de SinergiaCRM e, por outra, nodos de soporte que permiten agrupar módulos, enlazar a outras webs, etc. Estes últimos identifícanse mediante unha marca de cores na esquina inferior dereita. Para incluír un módulo no menú principal debe estar <a href="index.php?module=Administration&action=ConfigureTabs" target="_blank">habilitado</a>. Se xa o está, pode arrastralo desde a zona de módulos non incluídos (dereita) cara o nodo do menú onde desexe que apareza (esquerda). Para reorganizar o menú, arrastra calquera elemento ata a posición desexada. Co botón dereito do rato pódese mostrar o menú contextual asociado a cada nodo, que permitirá crear novos nodos (que no caso dos nodos de soporte poden apuntar a calquera URL), duplicalos, cambiar o seu nome (só no caso de nodos de apoio) ou eliminalos. A modificación dos nomes dos módulos debe facerse en <a href="index.php?action=wizard&module=Studio&wizard=StudioWizard&option=RenameTabs">Renomear pestanas</a>.';
$mod_strings['LBL_STIC_MENU_ICONS'] = 'Amosar iconas de módulos';
$mod_strings['LBL_STIC_MENU_ALL'] = 'Amosar opción TODO';
$mod_strings['LBL_STIC_MENU_COMMAND_CREATE'] = 'Crear';
$mod_strings['LBL_STIC_MENU_COMMAND_CREATE_DEFAULT'] = 'Novo nodo';
$mod_strings['LBL_STIC_MENU_COMMAND_RENAME'] = 'Renomear';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL'] = 'Editar o URL';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL_PROMPT'] = 'Escriba o URL';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL_PROMPT_VALIDATE'] = 'Escriba un URL válido';
$mod_strings['LBL_STIC_MENU_COMMAND_REMOVE'] = 'Eliminar';
$mod_strings['LBL_STIC_MENU_COMMAND_DUPLICATE'] = 'Duplicar';
$mod_strings['LBL_STIC_MENU_COMMAND_NEW_MAIN_NODE'] = 'Novo nodo principal';
$mod_strings['LBL_STIC_MENU_COMMAND_EXPAND'] = 'Expandir árbore';
$mod_strings['LBL_STIC_MENU_COMMAND_COLLAPSE'] = 'Contraer árbore';

// Cadenas de SuiteCRM modificadas
$mod_strings['LBL_CONFIGURE_GROUP_TABS'] = 'Agrupación de subpaneis';
$mod_strings['LBL_CONFIGURE_GROUP_TABS_DESC'] = 'Configurar o xeito en que se agrupan os subpaneis na vista de detalle';

// Otras cadenas
$mod_strings['LBL_TRACKERS_TITLE'] = 'Monitorización';
$mod_strings['LBL_TRACKERS_DESCRIPTION'] = 'Registro de las sesiones de usuario y de las acciones sobre registros.';
$mod_strings['LBL_ADMIN_ACTIONS'] = 'Accións de Administración';
$mod_strings['ERR_SYS_GEN_PWD_TPL_NOT_SELECTED'] = 'Especifique o modelo de correo que se enviará ao crear un usuario.';

// Configuración de SinergiaDA
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_SECTION'] = 'Configuración de SinergiaDA';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_GENERAL'] = 'Configuración xeral';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_CACHE'] = 'Caché de SinergiaDA';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_SAVE'] = 'Gardar configuración';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_SAVE_HELP'] = 'Aplica a configuración xeral e de caché de SinergiaDA';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_SAVE_SUCCESS'] = 'Configuración gardada correctamente.';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_SAVE_ERROR'] = 'Erro ao gardar a configuración. Revise o log para máis detalles.';
$mod_strings['LBL_STIC_SINERGIADA_ENABLED_LABEL'] = 'Activar SinergiaDA';
$mod_strings['LBL_STIC_SINERGIADA_ENABLED_HELP'] = 'Activa ou desactiva a funcionalidade de SinergiaDA. Se está desactivada, non se mostran os menús correspondentes nin é posible a sincronización.';
$mod_strings['LBL_STIC_SINERGIADA_URL_LABEL'] = 'URL da instancia';
$mod_strings['LBL_STIC_SINERGIADA_URL_HELP'] = 'URL onde se atopa a instancia de SinergiaDA. Úsase para ligazóns e para construír a chamada a updateModel. Se non se indica, asúmese unha instalación estándar en servidores de SinergiaTIC.';
$mod_strings['LBL_STIC_SINERGIADA_SEED_STRING_LABEL'] = 'Cadea de encriptación';
$mod_strings['LBL_STIC_SINERGIADA_SEED_STRING_HELP'] = 'Cadea aleatoria usada por SinergiaDA para encriptar valores na base de datos e asegurar a chamada a updateModel.';
$mod_strings['LBL_STIC_SINERGIADA_PUBLISH_AS_TABLE_LABEL'] = 'Publicar datos como táboas';
$mod_strings['LBL_STIC_SINERGIADA_PUBLISH_AS_TABLE_HELP'] = 'Seleccione os módulos que se publicarán como táboas en SinergiaDA. Os módulos NON seleccionados publicaranse como vistas MySQL.';
$mod_strings['LBL_STIC_SINERGIADA_MAX_USERS_LABEL'] = 'Límite de usuarios non administradores';
$mod_strings['LBL_STIC_SINERGIADA_MAX_USERS_HELP'] = 'Número máximo de usuarios non administradores a procesar. Baleiro ou 0 = sen límite.';
$mod_strings['LBL_STIC_SINERGIADA_GROUP_PERMISSIONS_LABEL'] = 'Permisos por grupo';
$mod_strings['LBL_STIC_SINERGIADA_GROUP_PERMISSIONS_HELP'] = 'Determina se SinergiaDA usará os permisos de acceso a rexistros dos grupos de seguridade de SinergiaCRM.';
$mod_strings['LBL_STIC_SINERGIADA_AUTO_REBUILD_LABEL'] = 'Reconstrución automática';
$mod_strings['LBL_STIC_SINERGIADA_AUTO_REBUILD_HELP'] = 'Executa automaticamente a reconstrución tras eliminar un campo en Studio ou desinstalar un módulo.';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_ENABLED_LABEL'] = 'Activar caché';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_ENABLED_HELP'] = 'Activa ou desactiva a caché de SinergiaDA.';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_UNITS_LABEL'] = 'Unidade de tempo';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_UNITS_HELP'] = 'Unidade de tempo para a rexeneración da caché (days u hours).';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_QUANTITY_LABEL'] = 'Cantidade';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_QUANTITY_HELP'] = 'Número de días ou horas entre rexeneracións de caché.';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_HOURS_LABEL'] = 'Hora de rexeneración';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_HOURS_HELP'] = 'Hora de rexeneración da caché (formato HH, ex. 04).';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_MINUTES_LABEL'] = 'Minuto de rexeneración';
$mod_strings['LBL_STIC_SINERGIADA_CACHE_MINUTES_HELP'] = 'Minuto de rexeneración da caché (formato MM, ex. 30).';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_EXTRA'] = 'Outras configuracións';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_EXTRA_KEY'] = 'Nova clave';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_EXTRA_VALUE'] = 'Valor';
$mod_strings['LBL_STIC_SINERGIADA_CONFIG_EXTRA_ADD'] = 'Engadir configuración';

// Autenticación OAuth
$mod_strings['LBL_OAUTH_AUTHENTICATION_TITLE'] = 'Autenticación OAuth';
$mod_strings['LBL_OAUTH_AUTH_ENABLE'] = 'Activar autenticación OAuth';
$mod_strings['LBL_OAUTH_AUTH_ENABLE_HELP'] = 'Aa activar esta opción os usuarios poderán autenticarse mediante OAuth 2.0, ademais de facelo con usuario e contraseña. Despois de activala deberase configurar polo menos un dos proveedores externos. Para máis información, consulte a <a href="https://wiki.sinergiatic.org/index.php?title=Usuarios,_Roles,_Grupos_de_seguridad_y_Registro_de_cambios#Autenticaci%C3%B3n_OAuth" target="_blank">documentación</a>.';
