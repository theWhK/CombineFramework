<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit33b80e5f27af63e37fefd0d7ab6541a4
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'C' => 
        array (
            'Combine\\Modules\\' => 16,
            'Combine\\Main\\' => 13,
            'Combine\\Classes\\' => 16,
            'Combine\\Action\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Combine\\Modules\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Modules',
        ),
        'Combine\\Main\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
        'Combine\\Classes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Classes',
        ),
        'Combine\\Action\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Actions',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit33b80e5f27af63e37fefd0d7ab6541a4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit33b80e5f27af63e37fefd0d7ab6541a4::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}