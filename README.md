# al

## Downlaod

### Composer

```
composer require nathanwooten\al
```

### GitHub

```
https://github.com/nathanwooten/al
```

## Usage:

### Load

```php
<?php

require_once 'al.php';
```

### Load with Composer

```php
<?php

require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
```

### A Top File(s)

```php
nathanwooten\al\Al::path_( 'TOP_DIR', [ dirname( __FILE__ ) ] );
nathanwooten\al\Al::path_( 'TOP_FILE', [ 'TOP_DIR', 'top.php' ] );

$return = nathanwooten\al\Al::file_( 'TOP_FILE' );
...
```

### From Any Entry Point

```php
<?php

use nathanwooten\al\Al;

//... define public ...
//ie:
// PathFind.php is in all dirs
$pathFind = require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PathFind.php';
// define public
$public = $pathFind->pathFind( __FILE__, [ 'public_html' ] ) . DS . 'public_html' );
//...

Al::path_( 'PUBLIC_HTML', 'PUBLIC_HTML', $public );
Al::path_( 'TOP_FILE', 'PUBLIC_HTML', 'top.php' );

$containerOrApplication = Al::file_( 'TOP_FILE' );
return $containerOrApplication;

```

## Config

The config is FIFO or first-in, first-out.

Config can technically include any function but is meant to include methods of the Al object that are appended by an "_"
