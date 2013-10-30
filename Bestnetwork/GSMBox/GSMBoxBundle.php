<?php
namespace Bestnetwork\GSMBox;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Bestnetwork\GSMBox\DependencyInjection\Compiler\AddGatewayPluginsPass;

/**
 * Generic GSM Gateway Managwer
 *
 * @author Bestnetwork <reparto.sviluppo@bestnetwork.it>
 */
class GSMBoxBundle extends Bundle {

    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder->addCompilerPass(new AddGatewayPluginsPass());
    }
}
