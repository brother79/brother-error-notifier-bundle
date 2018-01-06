<?php

/*
 * This file is part of the Brother ErrorNotifier Bundle
 *
 * Copyright (C) Brother
 *
 * @author Brother <contact@brother.com>
 */

namespace Brother\ErrorNotifierBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * BrotherErrorNotifier Extension
 */
class BrotherErrorNotifierExtension extends Extension
{
    /**
     * load configuration
     *
     * @param array            $configs   configs
     * @param ContainerBuilder $container container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        if (count($configs[0])) {
            $config = $this->processConfiguration($configuration, $configs);

            $container->setParameter('brother.error_notifier.config', $config);

            $loader = new XmlFileLoader($container, new FileLocator(array(__DIR__ . '/../Resources/config/')));
            $loader->load('services.xml');

            if ($config['mailer'] != 'mailer') {
                $definition = $container->getDefinition('brother.error_notifier.listener');
                $definition->replaceArgument(0, new Reference($config['mailer']));
            }
        }
    }
}
