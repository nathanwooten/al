<?php

/**
 * entry.php file,
 * located in directories with dependencies
 */

namespace nathanwooten\pathfind;

if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

if ( ! class_exists( 'nathanwooten\pathfind\PathFind' ) ) {
class PathFind
{

  public function pathFind( $directory, array $targetDirectoryContains )
  {

    $directory = (string) $directory;
    if ( ! is_string( $directory ) ) {
      throw new Exception( 'Directory must be stringable' );
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

}
}

return new PathFind;