<?php

declare(strict_types=1);

namespace Netgen\Bundle\WebSummerCampBundle\Block\Plugin;

use Netgen\BlockManager\Block\BlockDefinition\Handler\Plugin;
use Netgen\BlockManager\Parameters\ParameterBuilderInterface;
use Netgen\BlockManager\Parameters\ParameterType\ChoiceType;
use Netgen\BlockManager\Standard\Block\BlockDefinition\Handler\TextHandler;

final class AlignPlugin extends Plugin
{
    public static function getExtendedHandlers(): array
    {
        return [TextHandler::class];
    }

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $builder->add(
            'wsc:align',
            ChoiceType::class,
            [
                'options' => [
                    'Left' => 'left',
                    'Center' => 'center',
                    'Right' => 'right',
                ],
                'multiple' => false,
                'label' => 'Align',
            ]
        );
    }
}
