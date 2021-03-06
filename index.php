<?php

/**
 * Bootstrap file
 * Requirements: PHP5.4+ only
 */

version_compare(PHP_VERSION, '5.4', '>=') or die('Works on PHP5.4+ only.');

define('BASE_PATH', __DIR__ . '/');

require 'Trial/Autoloader.php';

use Trial\Autoloader,
	Trial\App,
	Trial\Routing\Http\Request;

Autoloader::register();

(new App('App'))->boot()->dispatch(Request::fromUrl());