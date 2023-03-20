<?php

namespace nathanwooten\Http;

class Response
{

  public $body = '';
  public array $headers = [];

  public function __construct( $body = '', $headers = [] )
  {

    $this->body = $body ?? $this->body;
    $this->headers = $headers;

  }

  public function withBody( $body = '' )
  {

    return new static( $body, $this->getHeaders() );

  }

  public function withHeaders( array $headers = [] )
  {

    return new static( $this->getBody(), $headers );

  }

  public function getBody()
  {

    return $this->body;

  }

  public function getHeaders()
  {

    return $this->headers;

  }

}