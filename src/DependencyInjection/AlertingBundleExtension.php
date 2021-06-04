<?php

declare(strict_types=1);

namespace AdgoalCommon\AlertingBundle\DependencyInjection;

use AdgoalCommon\Alerting\Application\Processor\AlertEventProcessor;
use AdgoalCommon\Alerting\Infrastructure\Adapter\AlertaHttpRequestAdapter;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class AlertingBundleExtension.
 *
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @category DependencyInjection
 */
class AlertingBundleExtension extends ConfigurableExtension
{
    private const PROCCESOR_ADAPTER_ALERTA = 'alerta';

    /**
     * Configures the passed container according to the merged configuration.
     *
     * @param mixed[]          $mergedConfig
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('alerting.yml');

        $container->setParameter(
            'adgoal_alerting.project_name',
            $mergedConfig['project_name']
        );

        if (isset($mergedConfig['delaytime'])) {
            $container->setParameter(
                'adgoal_alerting.delaytime',
                $mergedConfig['delaytime']
            );
        }

        $container->setAlias(
            'adgoal_alerting.storage_repository',
            new Alias($mergedConfig['storage_repository'], true)
        );

        $this->configureProcessorAdapter($mergedConfig, $container);
    }

    /**
     * Configure the alerting processor adapter.
     *
     * @param mixed[]          $mergedConfig
     * @param ContainerBuilder $container
     */
    private function configureProcessorAdapter(array $mergedConfig, ContainerBuilder $container): void
    {
        if (self::PROCCESOR_ADAPTER_ALERTA === $mergedConfig['adapter']['type']) {
            $this->configureAlertaAdapter($mergedConfig, $container);
        }
    }

    /**
     * Configure the alerting processor alerta adapter.
     *
     * @param mixed[]          $mergedConfig
     * @param ContainerBuilder $container
     */
    private function configureAlertaAdapter(array $mergedConfig, ContainerBuilder $container): void
    {
        $processorAdapter = new Definition(AlertaHttpRequestAdapter::class, [
            $mergedConfig['adapter']['host'],
            $mergedConfig['adapter']['token'],
        ]);
        $processorAdapter->setPublic(false);
        $container->setDefinition('alerting.processor.adapter', $processorAdapter);

        if (isset($mergedConfig['adapter']['attributes'])) {
            $container->setParameter(
                'adgoal_alerting.adapter.attributes',
                $mergedConfig['adapter']['attributes']
            );
        }

        $container->setAlias(
            'adgoal.alerting.client_producer',
            new Alias($mergedConfig['client_producer'], true)
        );

        $errorEventProcessor = $container->getDefinition(AlertEventProcessor::class);
        $errorEventProcessor->addTag('enqueue.topic_subscriber', ['client' => $mergedConfig['client_name']]);
        $container->setDefinition(AlertEventProcessor::class, $errorEventProcessor);
    }

    /**
     * Returns the bundle configuration alias.
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'adgoal_alerting';
    }
}
