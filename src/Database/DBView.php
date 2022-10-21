<?php
namespace Phpnova\Nova\Database;

use Phpnova\Nova\Bin\ErrorCore;

class DBView
{
    public function get(){
        try {
            
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function getAll()
    {
        try {

        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}