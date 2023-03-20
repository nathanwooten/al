# al

## Simple Require:

```php
<?php

require_once 'al.php';
```

## From an Index File using Entry Point:

```php
<?php

$pathFind = require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'entry.php';
require $pathFind->pathFind( __FILE__, [ 'al.php' ] ) . DS . 'al.php';
```

The config is FIFO.

## Download

https://github.com/nathanwooten/al

 - or

## Composer

### Install

```
composer require nathanwooten\al
```

### Require all Composer Dependencies

```php
<?php

require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
```
