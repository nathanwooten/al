<?php

namespace nathanwooten\Http;

interface RequestInterface {

  public function getBody();
  public function getParams();

}