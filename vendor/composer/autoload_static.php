<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit90c49eac456ae4d443176ccb1574bffb
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
    );

    public static $classMap = array (
        'AdminController' => __DIR__ . '/../..' . '/controller/AdminController.php',
        'CsrfVerify' => __DIR__ . '/../..' . '/src/CsrfVerify.php',
        'Database\\MySql' => __DIR__ . '/../..' . '/src/Database/MySql.php',
        'Database\\MySqlCredentialBuilder' => __DIR__ . '/../..' . '/src/Database/MySqlCredentialBuilder.php',
        'ExternalData\\DataFactory' => __DIR__ . '/../..' . '/src/ExternalData/DataFactory.php',
        'ExternalData\\ObtainDataInterface' => __DIR__ . '/../..' . '/src/ExternalData/ObtainDataInterface.php',
        'ExternalData\\PropertiesData' => __DIR__ . '/../..' . '/src/ExternalData/PropertiesData.php',
        'Properties' => __DIR__ . '/../..' . '/models/Properties.php',
        'Settings' => __DIR__ . '/../..' . '/src/Settings.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit90c49eac456ae4d443176ccb1574bffb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit90c49eac456ae4d443176ccb1574bffb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit90c49eac456ae4d443176ccb1574bffb::$classMap;

        }, null, ClassLoader::class);
    }
}
