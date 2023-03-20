<?php

$entry = require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'entry.php';
require $entry->pathFind( __FILE__, [ DS . 'al.php' ] ) . DS . 'al.php';
Al::setCallbacks( 'config.php' );
Al::run();

$uri = new nathanwooten\Http\Uri;
var_dump( $uri );