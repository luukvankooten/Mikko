#!/usr/bin/env php
<?php

use Mikko\Command\Payday;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$app = new Application('Mikko', '1.0.0');

$app->add(new Payday());

$app->run();


