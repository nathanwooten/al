# al

From an index file:

```php
<?php

$pathFind = require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'entry.php';
require $pathFind->pathFind( __FILE__, [ 'al.php' ] ) . DS . 'al.php';
```

The config is FIFO.

## Composer

### Install

```
composer require nathanwooten\al
```

### Require all Composer Dependencies

```
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
```
