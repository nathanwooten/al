<?php

namespace nathanwooten\Al;

use Exception;

if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

//////////////////////////////////////////////////

require dirname( __FILE__ ) . DS . 'entry.php';


if ( ! class_exists( 'nathanwooten\Al\Al' ) ) {
class Al
{

  /**
   * Supports both PSR autoloading standards
   */

  const SUPPORTS_ = [ 4 => 'PSR-4', 0 => 'PSR-0' ];

  const NAMESPACE_ = 'namespace';
  const DIRECTORY_ = 'directory';
  const SUPPORT_ = 'support';

  /**
   * The interface_ keys
   */

  const INTERFACE_KEYS = [
    self::NAMESPACE_ => 0,
    self::DIRECTORY_ => 1,
    self::SUPPORT_ => 2
  ];

  /**
   * The list of callbacks to generate
   * interfaces, path and files
   */

  public static $callbacks = [];

  /**
   * The callback pointer
   */

  public static $pointer = -1;

  /**
   * The interfaces loaded
   */

  public static $interface_ = [];

  /**
   * Paths and filepaths saved
   */

  public static $path_ = [];

  /**
   * Contents of file includes
   */

  public static $file_ = [];

  /**
   * The interfaces & callbacks map
   */

  public static $map = [];

  /**
   * The path finder instance
   */

  public static \nathanwooten\pathfind\PathFind $pathFind;


  /**
   * Registered flag
   */

  protected static bool $registered = false;

  /**
   * Call with current settings
   */

  protected static function prepare()
  {

    if ( ! static::$registered ) {
      spl_autoload_register( [ __CLASS__, 'load' ], true, true );
      static::$registered = true;
    }

  }

  public static function run()
  {

    static::prepare();

    $result = [];

    $callbacks = static::callbackGet();

    $i = ++ static::$pointer;
    while( $callbacks ) {

      $callbackArray = array_pop( $callbacks );

      $result[ $i ] = static::call( $callbackArray[0], $callbackArray[1] );
      $i++;
    }

    static::$pointer = $i;

    if ( 1 === count( $result ) ) {
      $result = current( $result );
    }

    return $result;

  }

  public static function call( $callback, $parameters )
  {

    if ( ! is_callable( $callback ) ) {
      $callback = static::callbackNormal( $callback );
    }

    if ( ! is_array( $parameters ) && is_callable( $parameters ) ) {
      $parameters = $parameters();
    }

    $parameters = array_values( $parameters );

    $result = $callback( ...$parameters );

    return $result;

  }

  public static function setCallbacks( array|string $callbacks )
  {

    if ( ! is_array( $callbacks ) ) {

      $file = static::pathAppend( static::pathFind( __FILE__, [ $callbacks ] ), $callbacks );
      if ( ! $file || ! is_readable( $file ) ) {
        throw new Exception( '$callbacks file not found' );
      }

      $callbacks = require $file;
      if ( ! is_array( $callbacks ) ) {
        throw new Exception( '$callbacks must resolve to a array of callbacks' );
      }
    }

    foreach ( $callbacks as $callbackArray ) {
      static::setCallback( $callbackArray[0], $callbackArray[1] );
    }

  }

  public static function setCallback( $callback, $args, $normal = true )
  {

    if ( $normal ) {
      $normal = static::callbackNormal( $callback );
    }

    static::$callbacks[] = [ $normal, $args ];

    $parsed = static::parseArgs( $normal, $args );
    $count = count( static::$callbacks );

    $id = 0 === $count ? 0 : --$count;

    foreach ( $args as $property ) {
      static::map( static::callbackRemove( $normal ), $property, $id );
    }

  }

  public static function getCallback( $callback, $property )
  {

    $index = static::map( static::callbackName( $callback ), $property );

    if ( $index && array_key_exists( $index, static::$callbacks ) ) {
      return static::$callbacks[ $index ];
    }

  }

  public static function callbackGet()
  {

    if ( 0 > static::$pointer ) {
      return static::$callbacks;
    }

    return array_slice( static::$callbacks, static::$pointer );

  }

  public static function callbackAll()
  {

    return static::$callbacks;

  }

  public static function callbackReset()
  {

    static::$callbacks = [];

  }

  public static function callbackNormal( $callback )
  {

    return static::callbackAdd( $callback );

  }

  public static function callbackName( $callback )
  {

    if ( is_string( $callback ) ) {
    } else {
      if ( is_array( $callback ) ) {
        $callback = array_values( $callback );

        if ( ! isset( $callback[1] ) ) {
          return null;
        }

        $callback = $callback[1];
      }
    }

    $name = (string) $callback;

    return $name;

  }

  public static function callbackAdd( string $callback )
  {

    $name = __CLASS__ . '::' . static::callbackRemove( $callback );
    return $name;

  }

  public static function callbackRemove( string $callback )
  {

    $class = __CLASS__ . '::';
    $callback = ( 0 === strpos( $callback, $class ) ? substr( $callback, strlen( $class ) ) : $callback );

    $name = $callback;

    return $name;

  }

  public static function parseArgs( $normal, array $args = [] )
  {

    $name = static::callbackRemove( $normal );

    $methods = [ 'path_', 'file_' ];

    if ( in_array( $name, $methods ) ) {

      switch( $name ) {

        case 'path_':

          array_shift( $args );
          $args = current( $args );

          break;
      }

      foreach ( $args as $key => $arg ) {

        try {
          $arg = static::parseArg( $arg );
        } catch ( Exception $e ) {
          $arg = static::parseArg( $arg, false );
        }

        if ( $arg ) {
          $args[ $key ] = $arg;
        }
      }
    }

    return $args;

  }

  public static function parseArg( $name, $ready = true )
  {

    if ( $ready ) {
      $arg = static::path_( $name );

      if ( $arg ) {
        return $arg;
      }
    } else {

       $arg = function ( $arg ) {

         $method = __CLASS__ . '::' . 'parseArg';
         $result = $method( $arg, true );

         if ( $result ) {
           return $result;
         }

         return $arg;

       };
    }

  }

  public static function load( $interface )
  {

    static::prepare();

    $result = null;

    foreach ( static::$interface_ as $package ) {
      $namespace = $package[ static::getKey( 'namespace' ) ];
      $directory = $package[ static::getKey( 'directory' ) ];

      $namespace = static::pathNormal( $namespace, '', DS );
      $directory = static::pathNormal( $directory, null, DS );

      if ( 0 !== strpos( $interface, $namespace ) ) {
        continue;
      }

      $class = str_replace( $namespace, '', $interface );

      if ( 'PSR-0' === $package[ static::getKey( 'support_' ) ] ) {
        $class = str_replace( '_', DIRECTORY_SEPARATOR, $class );
      }

      $file = $directory . $class . '.php';

      if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
        continue;
      }

      $result = require $file;
      break;

    }

    return $result;

  }

  // constant in lower without trailing underscore

  public static function getKey( $constant )
  {

    $key = constant( __CLASS__ . '::' . rtrim( strtoupper( $constant ), '_' ) . '_' );

    return array_key_exists( $key, static::INTERFACE_KEYS ) ? static::INTERFACE_KEYS[ $key ] : null;

  }

  // switch map

  public static function map( $name, $key, $value = null )
  {

    $mapped = null;

    $name = is_array( $name ) ? static::pathAppend( ...$name ) : $name;
    $key = is_array( $key ) ? static::pathAppend( ...$key ) : $key;

    if ( isset( $value ) ) {
      $mapped = $value;

      static::$map[ $name ][ $key ] = $value;
      static::$map[ $key ][ $name ] = $value;

    } else {

      $map = static::getMap();

      if ( array_key_exists( $name, $map ) ) {

        if ( array_key_exists( $key, $map ) ) {
        $mapped =& $map[ $name ][ $key ];
        } else {
          throw new Exception( 'Name was found in the base position but key was not found in the next' );
        }

      } elseif ( array_key_exists( $key, $map ) ) {

        if ( array_key_exists( $name, $map ) ) {
          $mapped =& $map[ $key ][ $name ];
        } else {
          throw new Exception( 'Key was found in the base position but name was not found in the next' );
        }

      } else {
        throw new Exception( 'No index by that name Or key exists in the map' );
      }
    }

    return $mapped;

  }

  public static function getMap()
  {

    return static::$map;

  }

  public static function pathNormal( $path, $before = '', $after = '', $separator = DIRECTORY_SEPARATOR )
  {

    $path = str_replace( [ '\\', '/' ], $separator, $path );

    if ( isset( $before ) ) {
      $path = ltrim( $path, $separator );
      if ( ! empty( $before ) ) {
        $before = $separator;
        $path = $before . $path;
      }
    }

    if ( isset( $after ) ) {
      $path = rtrim( $path, $separator );
      if ( ! empty( $after ) ) {
        $after = $separator;
        $path .= $after;
      }
    }

    return $path;

  }

  public static function pathAppend( $path, ...$append )
  {

    $trim = '\\/';

    $path = rtrim( $path, $trim );

    foreach ( $append as $item ) {
      $path .= DIRECTORY_SEPARATOR . trim( $item, $trim );
    }

    $path = ltrim( $path, $trim );

    return $path;

  }

  public static function pathFind( $directory, array $targetDirectoryContains )
  {

    if ( ! isset( static::$pathFind ) ) {
      static::$pathFind = new \nathanwooten\pathfind\PathFind;
    }

    return static::$pathFind->pathFind( $directory, $targetDirectoryContains );

  }

  public static function interface_( $namespace, $directory, $support = self::SUPPORT_[4] )
  {

    try {
      $mapped = static::map( $namespace, $directory );
      if ( $mapped && array_key_exists( $mapped, static::$interface_ ) ) {
        return static::$interface_[ $mapped ];
      }
    } catch( Exception $e ) {      
    }

    $package = null;
    $packages = [];

    $package = [];

    $package[ static::getKey( 'namespace' ) ] = $namespace;

    try {
      $path = static::path_( $directory );
    } catch ( Exception $e ) {
    }

    if ( isset( $path ) && $path ) {
      $directory = $path;
    }

    $package[ static::getKey( 'directory' ) ] = $directory;
    $package[ static::getKey( 'support' ) ] = $support;

    $index = count( static::$interface_ );
    $index = 0 === $index ? $index : --$index;

//    static::map( $namespace, $directory, $index );

    static::$interface_[] = $package;

    return count( static::$interface_ ) -1;

  }

  public static function path_( $name, array $append = [] )
  {

    if ( array_key_exists( $name, static::$path_ ) ) {
      return static::$path_[ $name ];
    }

    if ( empty( $append ) ) {
      throw new Exception( 'Unavailable, ' . $name );
    }

    $path = ''; 

    foreach ( $append as $app ) {

      if ( array_key_exists( $app, static::$path_ ) ) {
        $add = static::$path_[ $app ];
      } else {
        $add = $app;
      }
      $path = static::pathAppend( $path, $add );

      if ( ! is_readable( $path ) ) {
        throw new Exception( 'Path not readable, ' . $path );
      }
    }

    $path = trim( $path, DS	);

    static::$path_[ $name ] = $path;

    return $path;

  }

  public static function file_( $file )
  {

    if ( isset( static::$file_[ $file ] ) ) {
    } else {

      if ( ! is_readable( $file ) ) {
        $file = static::path_( $file );
      }

      if ( $file && is_file( $file ) && is_readable( $file ) ) {
      } else {
        throw new Exception( 'File not actionable, ' . $file );
      }
   }

   static::$file_[ $file ] = require $file;

   return static::$file_[ $file ];

  }

}
}

//////////////////////////////////////////////////

Al::setCallbacks( 'config.php' );
Al::run();