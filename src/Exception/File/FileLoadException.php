<?php

namespace App\Exception\File;

use Exception;

class FileLoadException extends Exception
{
    protected $message = 'File load exception';
}
