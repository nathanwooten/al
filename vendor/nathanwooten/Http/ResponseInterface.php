<?php

namespace nathanwooten\Http;

interface ResponseInterface
{

  public function withBody( $body = '' );
  public function withHeaders( array $headers = [] );
  public function getBody();
  public function getHeaders();

}
