<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DeclarativeFactory\DeclarativeFactory;

echo PHP_EOL;
echo json_encode(
  [
    DeclarativeFactory::factory([
      [
        false,
        function () {
          return 1;
        },
      ],
      [
        function () {
          return true;
        },
        2,
      ],
      3,
    ]),
  ],
  JSON_PRETTY_PRINT
);
echo PHP_EOL;

echo PHP_EOL;
