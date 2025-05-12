<?php
/**
 * CakePHP(tm) : Framework de Desarrollo Rápido (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licenciado bajo la Licencia MIT
 * Para obtener la información completa sobre derechos de autor y licencia, consulta LICENSE.txt
 * Las redistribuciones de archivos deben retener el aviso de derechos de autor anterior.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org Proyecto CakePHP(tm)
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php Licencia MIT
 */

/*
 * Este archivo es cargado por el método bootstrap de tu archivo src/Application.php.
 * Siéntete libre de extender o extraer partes del proceso de arranque a tus propios archivos
 * para ajustarlo a tus necesidades o preferencias.
 */

/*
 * Configurar las rutas necesarias para encontrar CakePHP + constantes de rutas generales
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'paths.php';

/*
 * Inicializar CakePHP
 * Actualmente, todo esto hace es inicializar el enrutador (sin cargar tus rutas).
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;

/*
 * Cargar funciones globales para colecciones, traducciones, depuración, etc.
 */
require CAKE . 'functions.php';

/*
 * Consulta https://github.com/josegonzalez/php-dotenv para detalles de la API.
 *
 * Descomenta el bloque de código de abajo si deseas usar el archivo `.env` durante el desarrollo.
 * Deberías copiar `config/.env.example` a `config/.env` y establecer/modificar las
 * variables según sea necesario.
 *
 * El propósito del archivo .env es emular la presencia de las variables de entorno
 * como estarían en producción.
 *
 * Si usas archivos .env, ten cuidado de no cometerlos al control de versiones para evitar
 * riesgos de seguridad. Consulta https://github.com/josegonzalez/php-dotenv#general-security-information
 * para obtener más información sobre las prácticas recomendadas.
*/
// if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
//     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
//     $dotenv->parse()
//         ->putenv()
//         ->toEnv()
//         ->toServer();
// }

/*
 * Inicializa el almacén de configuración por defecto y carga el archivo de configuración principal (app.php)
 *
 * CakePHP contiene 2 archivos de configuración después de la creación del proyecto:
 * - `config/app.php` para la configuración predeterminada de la aplicación.
 * - `config/app_local.php` para la configuración específica del entorno.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Cargar un archivo de configuración local del entorno para proporcionar sobrecargas a tu configuración.
 * Nota: Por razones de seguridad, app_local.php **no debe** incluirse en tu repositorio git.
 */
if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

/*
 * Cuando debug = true, la caché de metadatos solo debe durar un tiempo corto.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_translations_.duration', '+2 minutes');
}

/*
 * Establecer la zona horaria predeterminada del servidor. Usar UTC facilita los cálculos y conversiones de tiempo.
 * Consulta https://php.net/manual/es/timezones.php para obtener una lista de cadenas de zona horaria válidas.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configurar la extensión mbstring para usar la codificación correcta.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Establecer el idioma predeterminado. Esto controla cómo se formatean las fechas, los números y las monedas,
 * y establece el idioma predeterminado para las traducciones.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Registrar los manejadores de errores y excepciones de la aplicación.
 */
(new ErrorTrap(Configure::read('Error')))->register();
(new ExceptionTrap(Configure::read('Error')))->register();

/*
 * Configuración específica para CLI/Comandos.
 */
if (PHP_SAPI === 'cli') {
    // Establecer el fullBaseUrl para permitir la generación de URLs en los comandos.
    // Esto es útil cuando se envía un correo electrónico desde comandos.
    // Configure::write('App.fullBaseUrl', php_uname('n'));

    // Establecer los logs en archivos diferentes para evitar conflictos de permisos.
    if (Configure::check('Log.debug')) {
        Configure::write('Log.debug.file', 'cli-debug');
    }
    if (Configure::check('Log.error')) {
        Configure::write('Log.error.file', 'cli-error');
    }
}

/*
 * Establecer la URL base completa.
 * Esta URL se usa como base de todos los enlaces absolutos.
 * Puede ser muy útil para aplicaciones CLI/Commandline.
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
    /*
     * Al usar proxies o balanceadores de carga, las conexiones SSL/TLS pueden
     * terminarse antes de llegar al servidor. Si confías en el proxy,
     * puedes habilitar `$trustProxy` para depender del encabezado `X-Forwarded-Proto`
     * para determinar si generar URLs usando `https`.
     *
     * Consulta también https://book.cakephp.org/5/en/controllers/request-response.html#trusting-proxy-headers
     */
    $trustProxy = false;

    $s = null;
    if (env('HTTPS') || ($trustProxy && env('HTTP_X_FORWARDED_PROTO') === 'https')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if ($httpHost) {
        $fullBaseUrl = 'http' . $s . '://' . $httpHost;
    }
    unset($httpHost, $s);
}
if ($fullBaseUrl) {
    Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);

/*
 * Aplicar la configuración cargada a sus respectivos sistemas.
 * Esto también eliminará los datos de configuración cargados de la memoria.
 */
Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * Configurar detectores para dispositivos móviles y tabletas.
 * Si no usas estas comprobaciones, puedes eliminar de forma segura este código
 * y el paquete mobiledetect de composer.json.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});


