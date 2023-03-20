<?php

use nathanwooten\al\{

  Al

};

$return = Al::interface_( 'nathanwooten\Http', dirname( __FILE__ ) . DS . 'vendor' . DS . 'nathanwooten' . DS . 'Http' );
var_dump( $return );