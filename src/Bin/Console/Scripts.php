<?php
namespace Phpnova\Nova\Bin\Console;
use Composer\IO\IOInterface;
use Composer\Script\Event;

require __DIR__ . '/../Functions/.functions.php';

class Scripts
{
    private static IOInterface $io;
    private static array $args = [];
    private static string $dir = "";
    private static array $files = [];

    public static function run(Event $event)
    {
        self::$io = $event->getIO();
        self::$args = $event->getArguments();

        $cmd = self::getArg();

        self::$dir = dirname($event->getComposer()->getConfig()->getConfigSource()->getName());

        switch ($cmd) {
            case "i": require __DIR__ . '/script-install-workspace.php'; return;
            case "dbi": require __DIR__ . '/script-install-db.php'; return;
        }
    }

    public static function getArg(): ?string {
        return array_shift(self::$args);
    }

    public static function getIO(): IOInterface
    {
        return self::$io;
    }

    public static function getDir(): string
    {
        return self::$dir;
    }

    public static function filesAdd($name, $content): void
    {
        self::$files[] = [$name, $content];
    }

    public static function filesSave(): void
    {
        $len = strlen(self::$dir) + 1;
        foreach(self::$files as $file) {

            nv_create_dir(dirname($file[0]));

            $file_exists = $file[0];
            $fopen = fopen($file[0], $file_exists ? 'w' : 'a');
            fputs($fopen, $file[0]);
            fclose($fopen);

            if ($file_exists) {
                Console::fileUpdate($file[0]);
                continue;
            }
            Console::fileCreate($file[0]);
        }
    }
}