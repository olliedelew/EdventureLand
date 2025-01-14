<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbdd25dbb8f84d1c6cff55b341fb2a7b7
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitbdd25dbb8f84d1c6cff55b341fb2a7b7', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbdd25dbb8f84d1c6cff55b341fb2a7b7', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbdd25dbb8f84d1c6cff55b341fb2a7b7::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
