<?php
namespace Phpnova\Nova\Settings;

class Settings
{
    private Databases $databases;

    public function __construct()
    {
        $this->databases = new Databases();
    }

    /**
     * Estable el nombre del directorio donde se aguardaran los archivos
     */
    public function setDirUploadFiles(string $name): void
    {

    }

    public function getDatabases():Databases
    {
        return $this->databases;
    }

    // public function getDatabase(string $name = null):
}