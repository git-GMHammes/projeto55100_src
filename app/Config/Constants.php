<?php
/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
 defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

// TEMPORARIO: constantes do grupo 'habilidade' (Database.php) preenchidas com
// os mesmos envs locais/Docker (DB_HOST, DB_PORT, ...) usados no grupo
// codeigniter55100_mysql — nenhuma credencial fixa gravada aqui.
defined('D7E6F5A4B3C2D1E0F9G8H7I6J5K4L3M2') || define('D7E6F5A4B3C2D1E0F9G8H7I6J5K4L3M2', getenv('DB_HOST') ?: 'localhost');
defined('D7E6D5C4B3A201F9E8D7C6B5A4F3E2D1') || define('D7E6D5C4B3A201F9E8D7C6B5A4F3E2D1', getenv('DB_USERNAME') ?: '');
defined('A9F8E7D6C5B4A3F2E1D0C9B8A7F6E5D4') || define('A9F8E7D6C5B4A3F2E1D0C9B8A7F6E5D4', getenv('DB_PASSWORD') ?: '');
defined('F1E0D9C8B7A6F5E4D3C2B1A0F9E8D7C6') || define('F1E0D9C8B7A6F5E4D3C2B1A0F9E8D7C6', getenv('DB_DATABASE') ?: '');
defined('A0B1C2D3E4F50718293A4B5C6D7E8F90') || define('A0B1C2D3E4F50718293A4B5C6D7E8F90', 'MySQLi');
defined('A4F8B2C6E1D9A5F3C7E0B8D4A2F6C9E5') || define('A4F8B2C6E1D9A5F3C7E0B8D4A2F6C9E5', (int) (getenv('DB_PORT') ?: 3306));

/*
| --------------------------------------------------------------------------
| Composer Path
| --------------------------------------------------------------------------
|
| The path that Composer's autoload file is expected to live. By default,
| the vendor folder is in the Root directory, but you can customize that here.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'habilidade.com') {
    defined('DB_GROUP_001') || define('DB_GROUP_001', 'habilidade');
} else {
    defined('DB_GROUP_001') || define('DB_GROUP_001', 'codeigniter55100_mysql');
}

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR') || define('HOUR', 3600);
defined('DAY') || define('DAY', 86400);
defined('WEEK') || define('WEEK', 604800);
defined('MONTH') || define('MONTH', 2_592_000);
defined('YEAR') || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS') || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR') || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG') || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE') || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS') || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE') || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN') || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code
#
defined('DEBUG_MY_PRINT') || define('DEBUG_MY_PRINT', true);