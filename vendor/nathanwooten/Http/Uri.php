<?php

namespace nathanwooten\Http;

use nathanwooten\{

  Http\UriInterface

};

use Exception;

class Uri implements UriInterface
{

  protected $path = '';
  protected $query;
  protected array $params = [];

  public function __construct( $path = null, $query = null, array $params = [] )
  {

    $this->path = $path ?? $this->path;
    $this->query = $query ?? $this->query;
    $this->params = $params;

  }

  public function getPath()
  {

    return $this->path;

  }

  public function getQuery()
  {

    return $this->query;

  }

  public function getParams()
  {

    return $this->params;

  }

  public function getParam( $name )
  {

    return isset( $this->params[ $name ] ) ? $this->params[ $name ] : null;

  }

  public function removeParam( $name )
  {

    unset( $this->params[ $name ] );

  }

  public function getTarget()
  {

    $target = '';

    $path = $this->getPath();
    $query = $this->getQuery();

    if ( $path ) {
      $target .= $path;

    }
    if ( $query ) {
      $target .= '?' . $query;
 
    }

    return $target;

  }

  public function withPath( $path ) : UriInterface
  {

    $clone = clone $this;
    $clone->path = $path;
    return $clone;

  }

  public function withQuery( $query ) : UriInterface
  {

    $clone = clone $this;
    $clone->query = $query;
    return $clone;

  }

  public function withParams( array $params ) : UriInterface
  {

    $clone = clone $this;

    foreach ( $params as $name => $value ) {
      $clone->params[ $name ] = $value;

    }

    return $clone;

  }

  public function __toArray() : array
  {

    return [ $this->path, $this->querystring, $this->params ];

  }

}
