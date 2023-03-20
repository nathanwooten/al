# al

From an index file:

$pathFind = require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'entry.php';
require $pathFind->pathFind( __FILE__, [ 'al.php' ] ) . DS . 'al.php';
