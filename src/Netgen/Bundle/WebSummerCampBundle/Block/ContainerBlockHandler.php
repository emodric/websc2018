<?php

declare(strict_types=1);

namespace Netgen\Bundle\WebSummerCampBundle\Block;

use Netgen\BlockManager\Block\BlockDefinition\BlockDefinitionHandler;
use Netgen\BlockManager\Block\BlockDefinition\ContainerDefinitionHandlerInterface;

final class ContainerBlockHandler extends BlockDefinitionHandler implements ContainerDefinitionHandlerInterface
{
    public function getPlaceholderIdentifiers(): array
    {
        return ['left', 'right', 'bottom'];
    }
}
