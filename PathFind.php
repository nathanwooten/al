<?php

/**
 * PathFind.php file,
 * located in directories with dependencies
 *
 * MIT License
 * Copyright 2023 Nathan Wooten
 */

namespace nathanwooten\pathfind;

if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );

if ( ! class_exists( 'nathanwooten\pathfind\PathFind' ) ) {
class PathFind
{

  public static array $pathFind = [];

  public string $response;

  public string $path;
  public array $targetContains;

  public function pathFind( $path, array $targetContains ) : PathFind
  {

    $path = (string) $path;

    if ( ! is_string( $path ) ) {
      throw new Exception( 'Path must be stringable, ' . __CLASS__ . '::' . 'pathFind' );
    }

    if ( $has = $this->has( $path, $targetContains ) ) {
      return $has;
    }

    $this->path = $path;
    $this->targetContains = $targetContains;

    static::$pathFind[ $path ] = $this;
    static::$pathFind[ implode( ',', $targetContains ) ] = $this;

    if ( is_file( $path ) ) {
      $path = dirname( $path ) . DIRECTORY_SEPARATOR;
    }

    // no contents, no search
    if ( empty( $targetContains ) ) {
      return false;
    }

    while( $path && ( ! isset( $count ) || ! $count ) ) {

      $path = rtrim( $path, DIRECTORY_SEPARATOR . '\\/' ) . DIRECTORY_SEPARATOR;

      $is = [];

      // loop through 'contains'
      foreach ( $targetContains as $contains ) {
        $item = $path . $contains;

        // readable item?, add to $is
        if ( is_readable( $item ) ) {

          $is[] = $item;
 
        }
      }

      // expected versus is
      $isCount = count( $is );
      $containsCount = count( $targetContains );

      $count = ( $isCount === $containsCount );

      if ( $count ) {

        break;
      } else {

        $parent = dirname( $path );

        if ( $parent === $path ) {

          // if root reached break the loop
          throw new Exception( 'Reached root in, ' . __FILE__ . ' ' . __FUNCTION__ );

        } else {

          // continue up
          $path = $parent;

        }

        continue;
      }

    }

    if ( $path ) {
      $path = rtrim( $path, '\\/' );
    }

    $this->response = $path;

    return $this;

  }

  public function withPath( $path ) : PathFind
  {

    if ( $has = $this->has( $path, $this->targetContains ) ) {
      return $has;
    }

    $clone = clone $this;
    $clone->pathFind( $path, $this->targetContains );

    return $clone;

  }

  public function withContains( array $targetContains ) : PathFind
  {

    if ( $has = $this->has( $this->path, $targetContains ) ) {
      return $has;
    }

    $clone = clone $this;
    $clone->pathFind( $this->path, $targetContains );

    return $clone;

  }

  public function has( $path, $targetContains )
  {

    if ( isset( static::$pathFind[ $path ] ) && static::$pathFind[ $path ]->targetContains === $targetContains ) {
      return static::$pathFind[ $path ];
    }

    $tc = implode( ',', $targetContains );

    if ( $tc && isset( static::$pathFind[ $tc ] ) && static::$pathFind[ $tc ]->path === $path ) {
      return static::$pathFind[ $tc ];
    }

    return false;

  }

  public function __toString()
  {

    return $this->response;

  }

}
}