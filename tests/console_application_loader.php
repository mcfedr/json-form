<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Console\Application;

require __DIR__.'/../vendor/autoload.php';

$kernel = new TestKernel('test', true);
$application = new Application($kernel);

return $application;
