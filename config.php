<?php

// FIFO first in, first out

$al_callbacks = [
  [
    'file_',
    [
      'TOP_FILE'
    ]
  ],
  [
    'path_',
    [
      'TOP_FILE',
      [ 'PUBLIC_HTML', 'top.php' ]
    ]
  ],
  [
    'path_',
    [
      'PUBLIC_HTML',
      [ dirname( __FILE__ ) ]
    ]
  ]
];

return $al_callbacks;