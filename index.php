<?php
/**
 * simple package for learning purpose
 */

use Afs\Src\Main;

/**
 * required composer autoload file
 */
require "vendor/autoload.php";

echo (new Main)->sayHello();