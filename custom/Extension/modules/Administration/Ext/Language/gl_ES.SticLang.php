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

$mod_strings['LBL_STIC_SETTINGS_LINK_TITLE'] = 'Configuración';
$mod_strings['LBL_STIC_SETTINGS_DESCRIPTION'] = 'Opcións de configuración de SinergiaCRM.';

$mod_strings['LBL_STIC_TEST_DATA_LINK_TITLE'] = 'Datos de proba';
$mod_strings['LBL_STIC_TEST_DATA_DESCRIPTION'] = 'Cargar ou eliminar datos de proba.';

$mod_strings['LBL_STIC_SINERGIADA_LINK_TITLE'] = 'Sinergia Data Analytics';
$mod_strings['LBL_STIC_SINERGIADA_DESCRIPTION'] = 'Reconstrúe a integración con Sinergia Data Analytics.';
$mod_strings['LBL_STIC_SINERGIADA_MAX_USERS_ERROR'] = 'Excedeuse o límite de usuarios non administradores en SinerxiaDA. Máximo permitido: <b>__max_users__</b>. Valor actual: <b>__enabled_users__</b>. Desactive os usuarios de máis e inténteo de novo.';

$mod_strings['LBL_STIC_MAIN_MENU_LINK_TITLE'] = 'Menú principal';
$mod_strings['LBL_STIC_MAIN_MENU_DESCRIPTION'] = 'Configuración de la estructura y el contenido del menú';

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
$mod_strings['LBL_STIC_GO_TO_SDA_LINK_TITLE'] = 'Ir a Sinergia Data Analytics';
$mod_strings['LBL_STIC_RUN_SDA_SUCCESS_MSG'] = 'A reconstrución de Sinergia Data Analytics completouse con éxito.';
$mod_strings['LBL_STIC_RUN_SDA_ERROR_MSG'] = 'Durante a reconstrución de Sinergia Data Analytics atopáronse os seguintes erros. Contacte co soporte técnico de SinergiaTIC se o considera necesario.';

// Menú principal avanzado
$mod_strings['LBL_STIC_MENU_CONFIGURE_TITLE'] = 'Configuración del menú principal';
$mod_strings['LBL_STIC_MENU_ENABLED_NOT_INCLUDED'] = 'Módulos habilitados no incluidos en el menú';
$mod_strings['LBL_STIC_MENU_ENABLED_INCLUDED'] = 'Configuración del menú';
$mod_strings['LBL_STIC_MENU_SAVE'] = 'Guardar y aplicar';
$mod_strings['LBL_STIC_MENU_RESTORE'] = 'Restaurar';
$mod_strings['LBL_STIC_MENU_RESTORE_CONFIRM'] = '¿Restaurar el menu prederminado de SinergiaCRM?';
$mod_strings['LBL_STIC_MENU_INFO'] = 'El menú principal contiene dos tipos de elementos: por un lado, accesos directos a los diferentes módulos de SinergiaCRM y, por el otro, nodos de soporte que pueden usarse para agrupar módulos, enlazar con otros sitios web, etc. Estos últimos se identifican mediante una marca de color en la esquina inferior derecha. Para incluir un módulo en el menú principal debe estar <a href="index.php?module=Administration&action=ConfigureTabs" target="_blank">habilitado</a>. Si ya lo está, puede arrastrarse de la zona de módulos no incluidos (derecha) hacia el nodo del menú donde se desee que aparezca (izquierda). Para reorganizar el menú, arrastre cualquier elemento a la posición deseada. Con el botón derecho del ratón se puede mostrar el menú contextual asociado a cada nodo, que le permitirá crear nuevos nodos (que en el caso de los de soporte pueden apuntar a cualquier URL), duplicarlos, cambiar su nombre (solo en el caso de los nodos de soporte) o eliminarlos. La modificación de nombres de módulos debe hacerse en <a href="index.php?action=wizard&module=Studio&wizard=StudioWizard&option=RenameTabs">Renombrar pestañas</a>.';
$mod_strings['LBL_STIC_MENU_ICONS'] = 'Mostrar iconos de módulos';
$mod_strings['LBL_STIC_MENU_ALL'] = 'Mostrar la opción TODO';
$mod_strings['LBL_STIC_MENU_COMMAND_CREATE'] = 'Crear';
$mod_strings['LBL_STIC_MENU_COMMAND_CREATE_DEFAULT'] = 'Nuevo nodo';
$mod_strings['LBL_STIC_MENU_COMMAND_RENAME'] = 'Renombrar';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL'] = 'Editar la URL';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL_PROMPT'] = 'Escriba la URL';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL_PROMPT_VALIDATE'] = 'Escriba una URL válida';
$mod_strings['LBL_STIC_MENU_COMMAND_REMOVE'] = 'Eliminar';
$mod_strings['LBL_STIC_MENU_COMMAND_DUPLICATE'] = 'Duplicar';
$mod_strings['LBL_STIC_MENU_COMMAND_NEW_MAIN_NODE'] = 'Nuevo nodo principal';
$mod_strings['LBL_STIC_MENU_COMMAND_EXPAND'] = 'Expandir árbol';
$mod_strings['LBL_STIC_MENU_COMMAND_COLLAPSE'] = 'Contraer árbol';

// Cadenas de SuiteCRM modificadas
$mod_strings['LBL_CONFIGURE_GROUP_TABS'] = 'Agrupación de subpaneles';
$mod_strings['LBL_CONFIGURE_GROUP_TABS_DESC'] = 'Configuración de la agrupación de los subpaneles en las vistas de detalle';

// Otras cadenas
$mod_strings['LBL_ADMIN_ACTIONS'] = 'Accións de Administración';
$mod_strings['ERR_SYS_GEN_PWD_TPL_NOT_SELECTED'] = 'Especifique o modelo de correo que se enviará ao crear un usuario.';
