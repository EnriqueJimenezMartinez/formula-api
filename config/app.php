<?php

use Cake\Cache\Engine\FileEngine;
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Log\Engine\FileLog;
use Cake\Mailer\Transport\MailTransport;

return [
       /*
     * Nivel de depuración:
     *
     * Modo de producción:
     * falso: No se muestran mensajes de error, errores ni advertencias.
     *
     * Modo de desarrollo:
     * verdadero: Se muestran errores y advertencias.
     */
    'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),

    /*
     * Configura la información básica de la aplicación.
     *
     * - namespace - El espacio de nombres donde se encuentran las clases de la aplicación.
     * - defaultLocale - El idioma predeterminado para traducciones, formato de monedas y números, fecha y hora.
     * - encoding - La codificación utilizada para HTML y conexiones a la base de datos.
     * - base - El directorio base en el que reside la aplicación. Si es falso, se detecta automáticamente.
     * - dir - Nombre del directorio de la aplicación.
     * - webroot - El directorio webroot.
     * - wwwRoot - La ruta al archivo webroot.
     * - baseUrl - Para configurar CakePHP para que NO use mod_rewrite y use las URLs bonitas de CakePHP, elimina estos archivos .htaccess:
     *      /.htaccess
     *      /webroot/.htaccess
     *   Y descomenta la clave baseUrl a continuación.
     * - fullBaseUrl - Una URL base que se usará para enlaces absolutos. Cuando se configura como falso (por defecto),
     *   CakePHP genera el valor requerido basado en la variable de entorno `HTTP_HOST`.
     *   Sin embargo, puedes definirla manualmente para optimizar el rendimiento o si te preocupa que las personas manipulen el encabezado `Host`.
     * - imageBaseUrl - Ruta web al directorio público de imágenes/ dentro de webroot.
     * - cssBaseUrl - Ruta web al directorio público de css/ dentro de webroot.
     * - jsBaseUrl - Ruta web al directorio público de js/ dentro de webroot.
     * - paths - Configura rutas para recursos no basados en clases. Soporta las subclaves `plugins`, `templates`, `locales`,
     *   que permiten la definición de rutas para plugins, plantillas de vistas y archivos de locales respectivamente.
     */

    'App' => [
        'namespace' => 'App',
        'encoding' => env('APP_ENCODING', 'UTF-8'),
        'defaultLocale' => env('APP_DEFAULT_LOCALE', 'es'),
        'defaultTimezone' => env('APP_DEFAULT_TIMEZONE', 'Europe/Madrid'),
        'base' => false,
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        //'baseUrl' => env('SCRIPT_NAME'),
        'fullBaseUrl' => false,
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'plugins' => [ROOT . DS . 'plugins' . DS],
            'templates' => [ROOT . DS . 'templates' . DS],
            'locales' => [RESOURCES . 'locales' . DS],
        ],
    ],

     /*
     * Configuración de seguridad y encriptación
     *
     * - salt - Una cadena aleatoria utilizada en los métodos de hash de seguridad.
     *   El valor de salt también se usa como clave de encriptación.
     *   Debes tratarlo como un dato extremadamente sensible.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT'),
    ],

    /*
     * Aplicar marcas de tiempo con la última hora de modificación a los recursos estáticos (js, css, imágenes).
     * Se añadirá un parámetro de consulta que contiene el tiempo en que se modificó el archivo.
     * Esto es útil para forzar el borrado de la caché del navegador.
     *
     * Configura como verdadero para aplicar marcas de tiempo cuando debug sea verdadero. Configura como 'force' para habilitar siempre
     * las marcas de tiempo independientemente del valor de debug.
     */
    'Asset' => [
        //'timestamp' => true,
        // 'cacheTime' => '+1 year'
    ],

    /*
     * Configura los adaptadores de caché.
     */
    'Cache' => [
        'default' => [
            'className' => FileEngine::class,
            'path' => CACHE,
            'url' => env('CACHE_DEFAULT_URL', null),
        ],

        /*
         * Configura la caché utilizada para la caché general del framework.
         * Los archivos de caché de traducción se almacenan con esta configuración.
         * La duración se establecerá en '+2 minutos' en bootstrap.php cuando debug = true
         * Si configuras 'className' => 'Null', se desactivará la caché central.
         */
        '_cake_translations_' => [
            'className' => FileEngine::class,
            'prefix' => 'myapp_cake_translations_',
            'path' => CACHE . 'persistent' . DS,
            'serialize' => true,
            'duration' => '+1 years',
            'url' => env('CACHE_CAKECORE_URL', null),
        ],

        /*
         * Configura la caché utilizada para la caché de modelos y fuentes de datos. Esta configuración de caché
         * se usa para almacenar descripciones de esquema y listados de tablas en las conexiones.
         * La duración se establecerá en '+2 minutos' en bootstrap.php cuando debug = true
         */
        '_cake_model_' => [
            'className' => FileEngine::class,
            'prefix' => 'myapp_cake_model_',
            'path' => CACHE . 'models' . DS,
            'serialize' => true,
            'duration' => '+1 years',
            'url' => env('CACHE_CAKEMODEL_URL', null),
        ],
    ],

    /*
     * Configura los controladores de errores y excepciones utilizados por tu aplicación.
     *
     * Por defecto, los errores se muestran utilizando Debugger cuando debug es verdadero y se registran
     * con Cake\Log\Log cuando debug es falso.
     *
     * En entornos CLI, las excepciones se imprimirán en stderr con un seguimiento de pila.
     * En entornos web, se mostrará una página HTML para la excepción.
     * Con debug en verdadero, los errores del framework como "Falta el controlador" se mostrarán.
     * Cuando debug es falso, los errores del framework se convertirán en errores HTTP genéricos.
     *
     * Opciones:
     *
     * - `errorLevel` - int - El nivel de errores que te interesa capturar.
     * - `trace` - booleano - Si se deben incluir los rastros de pila en los errores/excepciones registrados.
     * - `log` - booleano - Si deseas que las excepciones se registren.
     * - `exceptionRenderer` - string - La clase responsable de mostrar las excepciones no capturadas.
     *   La clase seleccionada se utilizará para los entornos CLI y web. Si deseas clases diferentes para cada entorno,
     *   necesitarás escribir esa lógica condicional también.
     *   El lugar convencional para los renderizadores personalizados es en `src/Error`. Tu renderizador de excepciones debe
     *   implementar el método `render()` y devolver ya sea un string o una Http\Response.
     * - `errorRenderer` - string - La clase responsable de mostrar los errores PHP. La clase seleccionada se utilizará para ambos
     *   contextos web y CLI. Si deseas clases diferentes para cada entorno, necesitarás escribir esa lógica condicional también.
     *   Los renderizadores de errores deben implementar `Cake\Error\ErrorRendererInterface`.
     * - `skipLog` - array - Lista de excepciones que se deben omitir para el registro. Las excepciones que
     *   extiendan una de las excepciones listadas también serán omitidas para el registro.
     *   Ejemplo:
     *   `'skipLog' => ['Cake\Http\Exception\NotFoundException', 'Cake\Http\Exception\UnauthorizedException']`
     * - `extraFatalErrorMemory` - int - La cantidad de megabytes para aumentar el límite de memoria cuando se encuentra un error fatal.
     *   Esto permite tener espacio para completar el registro o el manejo de errores.
     * - `ignoredDeprecationPaths` - array - Una lista de rutas compatibles con globs que se deben ignorar para las deprecaciones.
     *   Usa esto para ignorar las deprecaciones de plugins o partes de tu aplicación que sigan generando deprecaciones.
     */
    'Error' => [
        'errorLevel' => E_ALL,
        'skipLog' => [],
        'log' => true,
        'trace' => true,
        'ignoredDeprecationPaths' => [],
    ],

    /*
     * Configuración del depurador
     *
     * Define los valores de error para desarrollo de Cake\Error\Debugger
     *
     * - `editor` Establece el formato de URL del editor que deseas usar.
     *   Por defecto, están disponibles atom, emacs, macvim, phpstorm, sublime, textmate y vscode.
     *   Puedes agregar formatos adicionales de enlace de editor usando
     *   `Debugger::addEditor()` durante el arranque de la aplicación.
     * - `outputMask` Un mapeo de `clave` a `reemplazo` que
     *   `Debugger` debe reemplazar en los datos y registros mostrados por `Debugger`.
     */
    'Debugger' => [
        'editor' => 'phpstorm',
    ],

    /*
        * Configuración de transporte de correo electrónico
        *
        * Define cómo se deben enviar los correos electrónicos. Los transportes incluyen `MailTransport`, `SmtpTransport`, etc.
        * Se configurará el transporte predeterminado a través de esta sección.
        */
    'EmailTransport' => [
        'default' => [
            'className' => MailTransport::class,

            'host' => 'localhost',
            'port' => 25,
            'timeout' => 30,

            //'username' => null,
            //'password' => null,
            'client' => null,
            'tls' => false,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],

        /*
    * Perfiles de entrega de correo electrónico
    *
    * Los perfiles de entrega te permiten predefinir varias propiedades sobre los mensajes
    * de correo electrónico de tu aplicación y darles un nombre a las configuraciones. Esto ahorra
    * duplicación a lo largo de la aplicación y facilita el mantenimiento y desarrollo.
    * Cada perfil acepta varias claves. Consulta `Cake\Mailer\Mailer`
    * para más información.
    */
    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => 'you@localhost',

        ],
    ],

    /*
 * Información de conexión utilizada por el ORM para conectarse
 * a los almacenes de datos de tu aplicación.
 *
 * ### Notas
 * - Los controladores incluyen Mysql, Postgres, Sqlite, Sqlserver
 *   Consulta vendor\cakephp\cakephp\src\Database\Driver para la lista completa
 * - No utilices puntos en el nombre de la base de datos, ya que puede causar errores.
 *   Consulta https://github.com/cakephp/cakephp/issues/6471 para más detalles.
 * - Se recomienda establecer 'encoding' a UTF-8 completo con soporte de 4 bytes.
 *   Ejemplo: ponlo en 'utf8mb4' en MariaDB y MySQL y 'utf8' para cualquier
 *   otro RDBMS.
 */
    'Datasources' => [
        /*
     * Estas configuraciones deberían contener configuraciones permanentes utilizadas
     * por todos los entornos.
     *
     * Los valores en app_local.php sobrescribirán los valores establecidos aquí
     * y deben usarse para configuraciones locales y por entorno.
     *
     * Las configuraciones basadas en variables de entorno pueden cargarse aquí o
     * en app_local.php dependiendo de las necesidades de la aplicación.
     */
        'default' => [
            'className' => Connection::class,
            'driver' => Mysql::class,
            'persistent' => false,
            'timezone' => 'UTC',


            'encoding' => 'utf8mb4',

            'flags' => [],
            'cacheMetadata' => true,
            'log' => false,
            'quoteIdentifiers' => false,


        ],

        'test' => [
            'className' => Connection::class,
            'driver' => Mysql::class,
            'persistent' => false,
            'timezone' => 'UTC',
            'encoding' => 'utf8mb4',
            'flags' => [],
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
            'log' => false,
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
        ],
    ],


    'Log' => [
        'debug' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'debug',
            'url' => env('LOG_DEBUG_URL', null),
            'scopes' => null,
            'levels' => ['notice', 'info', 'debug'],
        ],
        'error' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'error',
            'url' => env('LOG_ERROR_URL', null),
            'scopes' => null,
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        ],

        'queries' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'queries',
            'url' => env('LOG_QUERIES_URL', null),
            'scopes' => ['cake.database.queries'],
        ],
    ],

    /*
 * Configuración de la sesión.
 *
 * Contiene un array de configuraciones para usar en la configuración de la sesión. La
 * clave `defaults` se usa para definir una configuración predeterminada para las sesiones, cualquier
 * configuración declarada aquí sobrescribirá los ajustes de la configuración predeterminada.
 *
 * ## Opciones
 *
 * - `cookie` - El nombre de la cookie a usar. Por defecto, se usa el valor configurado en `session.name` de php.ini.
 *    Evita usar `.` en los nombres de las cookies, ya que PHP eliminará las sesiones de cookies con `.` en el nombre.
 * - `cookiePath` - La ruta URL para la que se establece la cookie de sesión. Se mapea con la
 *   configuración `session.cookie_path` de php.ini. Por defecto, es la ruta base de la aplicación.
 * - `timeout` - El tiempo en minutos que una sesión puede estar 'inactiva'. Si no se recibe una solicitud en
 *    este tiempo, la sesión se expirará y rotará. Pasa 0 para deshabilitar las comprobaciones de inactividad.
 * - `defaults` - La configuración predeterminada para usar como base para tu sesión.
 *    Existen cuatro opciones integradas: php, cake, cache, database.
 * - `handler` - Se puede usar para habilitar un controlador de sesión personalizado. Se espera un
 *    array con al menos la clave `engine`, que es el nombre de la clase del motor de sesión
 *    para usar en la gestión de la sesión. CakePHP incluye los motores `CacheSession`
 *    y `DatabaseSession`.
 * - `ini` - Un array asociativo de valores adicionales de 'session.*` para configurar.
 *
 * Dentro de la clave `ini`, probablemente querrás definir:
 *
 * - `session.cookie_lifetime` - El número de segundos durante los cuales las cookies son válidas. Esto
 *    debe ser mayor que `Session.timeout`.
 * - `session.gc_maxlifetime` - El número de segundos después de los cuales una sesión se considera 'basura'
 *    y puede ser eliminada por el comportamiento de limpieza de sesiones de PHP. Este valor debe ser mayor que ambos
 *    `Session.timeout` y `session.cookie_lifetime`.
 *
 * Las opciones integradas de `defaults` son:
 *
 * - 'php' - Usa configuraciones definidas en tu php.ini.
 * - 'cake' - Guarda archivos de sesión en el directorio /tmp de CakePHP.
 * - 'database' - Usa sesiones en la base de datos de CakePHP.
 * - 'cache' - Usa la clase Cache para guardar sesiones.
 *
 * Para definir un controlador de sesión personalizado, guárdalo en src/Http/Session/<nombre>.php.
 * Asegúrate de que la clase implemente la interfaz `SessionHandlerInterface` de PHP y configura
 * Session.handler a <nombre>
 *
 * Para usar sesiones en la base de datos, carga el archivo SQL ubicado en config/schema/sessions.sql
 */
    'Session' => [
        'defaults' => 'php',
    ],

    /**
 * Configuración de DebugKit.
 *
 * Contiene un array de configuraciones para aplicar al plugin DebugKit, si está cargado.
 * Documentación: https://book.cakephp.org/debugkit/5/es/index.html#configuracion
 *
 * ## Opciones
 *
 *  - `panels` - Habilitar o deshabilitar paneles. La clave es el nombre del panel, y el valor es verdadero para habilitar,
 *     o falso para deshabilitar.
 *  - `includeSchemaReflection` - Establecer a verdadero para habilitar el registro de consultas de reflexión de esquema. Deshabilitado por defecto.
 *  - `safeTld` - Establecer un array de TLDs permitidos para el desarrollo local.
 *  - `forceEnable` - Forzar la visualización de DebugKit. Ten cuidado con esto, generalmente es más seguro simplemente permitir
 *     tus TLDs locales.
 *  - `ignorePathsPattern` - Patrón regex (incluyendo delimitadores) para ignorar rutas.
 *     DebugKit no guardará datos para URLs de solicitudes que coincidan con este regex.
 *  - `ignoreAuthorization` - Establecer a verdadero para ignorar el plugin Cake Authorization para las solicitudes de DebugKit.
 *     Deshabilitado por defecto.
 *  - `maxDepth` - Define cuántos niveles de datos anidados deben mostrarse en general para la salida de depuración.
 *     El valor predeterminado es 5. ADVERTENCIA: Aumentar el nivel máximo de profundidad puede causar un error de falta de memoria.
 *  - `variablesPanelMaxDepth` - Define cuántos niveles de datos anidados deben mostrarse en la pestaña de variables.
 *     El valor predeterminado es 5. ADVERTENCIA: Aumentar el nivel máximo de profundidad puede causar un error de falta de memoria.
 */
    'DebugKit' => [
        'forceEnable' => filter_var(env('DEBUG_KIT_FORCE_ENABLE', false), FILTER_VALIDATE_BOOLEAN),
        'safeTld' => env('DEBUG_KIT_SAFE_TLD', null),
        'ignoreAuthorization' => env('DEBUG_KIT_IGNORE_AUTHORIZATION', false),
    ],
];
