<?php

declare(strict_types=1);

class TestKernel extends Symfony\Component\HttpKernel\Kernel
{
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Mcfedr\JsonFormBundle\McfedrJsonFormBundle(),
        ];
    }

    public function registerContainerConfiguration(Symfony\Component\Config\Loader\LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config_test.yml');
    }
}
