<?php

namespace nathanwooten\Http;

use nathanwooten\{

  Http\RequestInterface,
  Http\UriInterface

};

class Request implements RequestInterface
{

  public $body = '';
  public array $params = [];
  public UriInterface $uri;

  public function __construct( ?UriInterface $uri = null, array $params = null )
  {

    if ( ! $uri || ! $uri instanceof UriInterface ) {
      $uri = new Uri( $uri );
    }

    $this->uri = $uri;
    $this->params = $params ?? $this->params;

    $this->body = $this->name();

  }

  protected function name()
  {

    return $this->uri->getTarget();

  }

  public function withUri( $uri = null )
  {

    return new static( $uri, $this->getParams() );

  }

  public function withParams( array $params )
  {

    return new static( $this->getUri(), $params );

  }

  public function getUri()
  {

    return $this->uri;

  }

  public function getParams()
  {

    return $this->params;

  }

  public function getParam( $name )
  {

    if ( array_key_exists( $name, $this->params ) ) {
      return $this->params[ $name ];
    }

  }

  public function hasParam( $name )
  {

    return array_key_exists( $name, $this->params );

  }

  public function getBody()
  {

    return $this->body;

  }

  public function getData( $method )
  {

    global ${$method};
    if ( isset( ${$method} ) ) {
      return ${$method};
    }

    return [];

  }

  public function getTags()
  {

    return array_keys( $this->getParams() );

  }

}
