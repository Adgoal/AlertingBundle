<?php

declare(strict_types=1);

namespace AdgoalCommon\AlertingBundle;

use AdgoalCommon\AlertingBundle\DependencyInjection\AlertingBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AlertingBundle.
 *
 * @category AdgoalCommon\AlertingBundle
 */
class AlertingBundle extends Bundle
{
    /**
     * @return AlertingBundleExtension
     */
    public function getContainerExtension(): AlertingBundleExtension
    {
        return new AlertingBundleExtension();
    }
}
