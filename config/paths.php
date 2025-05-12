<?php
/**
 * CakePHP(tm): Framework de Desarrollo Rápido (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licenciado bajo la Licencia MIT
 * Las redistribuciones de archivos deben retener el aviso de derechos de autor anterior.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org Proyecto CakePHP(tm)
 * @since         3.0.0
 * @license       Licencia MIT (https://opensource.org/licenses/mit-license.php)
 */

/*
 * Usa DS para separar los directorios en otras definiciones
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/*
 * Estas definiciones solo deben editarse si tienes Cake instalado en
 * una estructura de directorios diferente a la que se distribuye.
 * Cuando uses configuraciones personalizadas, asegúrate de usar DS y no añadir un DS final.
 */

/*
 * La ruta completa al directorio que contiene "src", SIN un DS final.
 */
define('ROOT', dirname(__DIR__));

/*
 * El nombre real del directorio para el directorio de la aplicación. Normalmente
 * se llama 'src'.
 */
define('APP_DIR', 'src');

/*
 * Ruta al directorio de la aplicación.
 */
define('APP', ROOT . DS . APP_DIR . DS);

/*
 * Ruta al directorio de configuración.
 */
define('CONFIG', ROOT . DS . 'config' . DS);

/*
 * Ruta del archivo al directorio webroot.
 *
 * Para derivar tu webroot desde tu servidor web, cambia esto a:
 *
 * `define('WWW_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], DS) . DS);`
 */
define('WWW_ROOT', ROOT . DS . 'webroot' . DS);

/*
 * Ruta al directorio de pruebas.
 */
define('TESTS', ROOT . DS . 'tests' . DS);

/*
 * Ruta al directorio de archivos temporales.
 */
define('TMP', ROOT . DS . 'tmp' . DS);

/*
 * Ruta al directorio de registros.
 */
define('LOGS', ROOT . DS . 'logs' . DS);

/*
 * Ruta al directorio de archivos en caché. Puede ser compartido entre hosts en una configuración de servidor múltiple.
 */
define('CACHE', TMP . 'cache' . DS);

/*
 * Ruta al directorio de recursos.
 */
define('RESOURCES', ROOT . DS . 'resources' . DS);

/*
 * La ruta absoluta al directorio "cake", SIN un DS final.
 *
 * CakePHP siempre debe instalarse con composer, así que busca allí.
 */
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');

/*
 * Ruta al directorio cake.
 */
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);

