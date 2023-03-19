<?php

class PathHelper
{

  protected 


  protected function addPath( $name, array $append = [] )
  {

    $path = ''; 

    foreach ( $append as $app ) {

      if ( $this->hasPath( $app ) ) {
        $add = $this->getPath( $app );
      } else {
        $add = $app;
      }
      $path = $this->pathAppend( $path, $add );

      if ( ! is_readable( $path ) ) {
        return false;
      }
    }

    $path = trim( $path, DS	);

    $this->path[ $name ] = $path;

    return $path;

  }

  protected function getPath( $name )
  {

    return array_key_exists( $name, $this->path_ ) ? $this->path_[ $name ] : null;

  }

  protected function hasPath( $name )
  {

    return array_key_exists( $name, $this->path );

  }

  public function requireFile( $file )
  {

    if ( ! is_readable( $file ) ) {
      $file = $this->getPath( $file );
    }

    if ( $file && is_file( $file ) && is_readable( $file ) ) {
      return $this->file_[ $file ] = require_once $file;
    }

  }

  public function pathFind( $directory, array $targetDirectoryContains )
  {

    $directory = (string) $directory;
    if ( ! is_string( $directory ) ) {
      throw new Exception( 'An error has occurred, please contact the administrator. search 4805' );
    }

    if ( is_file( $directory ) ) {
      $directory = dirname( $directory ) . DIRECTORY_SEPARATOR;
    }

    // no contents, no search
    if ( empty( $targetDirectoryContains ) ) {
      return false;
    }

    while( $directory && ( ! isset( $count ) || ! $count ) ) {

      $directory = rtrim( $directory, DIRECTORY_SEPARATOR . '\\/' ) . DIRECTORY_SEPARATOR;

      $is = [];

      // loop through 'contains'
      foreach ( $targetDirectoryContains as $contains ) {
        $item = $directory . $contains;

        // readable item?, add to $is
        if ( is_readable( $item ) ) {

          $is[] = $item;
 
        }
      }

      // expected versus is
      $isCount = count( $is );
      $containsCount = count( $targetDirectoryContains );

      $count = ( $isCount === $containsCount );

      if ( $count ) {

        break;
      } else {

        $parent = dirname( $directory );

        if ( $parent === $directory ) {

          // if root reached break the loop
          throw new Exception( 'Reached root in, ' . __FILE__ . ' ' . __FUNCTION__ );

        } else {

          // continue up
          $directory = $parent;

        }

        continue;
      }

    }

    if ( $directory ) {
      $directory = rtrim( $directory, '\\/' );
    }

    return $directory;

  }

  public function pathNormal( $path, $before = '', $after = '', $separator = DIRECTORY_SEPARATOR )
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

  public function pathAppend( $path, ...$append )
  {

    $trim = '\\/';

    $path = rtrim( $path, $trim );

    foreach ( $append as $item ) {
      $path .= DIRECTORY_SEPARATOR . trim( $item, $trim );
    }

    return $path;

  }

}
}