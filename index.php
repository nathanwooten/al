<?php

( require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'entry.php' )->pathFind( __FILE__, [ DS . 'al.php' ] ) . DS . 'al.php';

print '<h1>Hello World</h1>';