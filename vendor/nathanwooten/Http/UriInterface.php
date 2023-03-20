<?php

namespace nathanwooten\Http;

interface UriInterface
{

  public function getPath();
  public function getQuery();
  public function getParams();
  public function getParam( $name );
  public function removeParam( $name );
  public function getTarget();
  public function withPath( $path ) : UriInterface;
  public function withQuery( $query ) : UriInterface;
  public function withParams( array $params ) : UriInterface;
  public function __toArray() : array;

}