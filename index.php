<?php

declare(strict_types = 1);

require "vendor/autoload.php";

use App\ErrorHandler;
use App\Router;

header("Content-Type: application/json");

set_exception_handler([ErrorHandler::class, 'handleException']);
set_error_handler([ErrorHandler::class, 'handleError']);

Router::route();
