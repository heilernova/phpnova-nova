<?php
namespace Phpnova\Nova\Bin;

use Exception;
use Throwable;

class Error extends Exception
{
    public function __construct(string|Throwable $arg)
    {
        if (is_string($arg)) {
            $this->message = $arg;
        } else {
            $this->message = $arg->getMessage();
            $this->code = $arg->getFile();
        }
        
        # Modificamos el archivo y la linea para que muestre el donde se ejecuta la función que crea el error
        $backtrace = debug_backtrace()[1] ?? null;
        if ($backtrace) {
            $this->file = $backtrace['file'];
            $this->line = $backtrace['line'];
        }
    }
}