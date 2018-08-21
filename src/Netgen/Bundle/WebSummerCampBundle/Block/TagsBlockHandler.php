<?php

declare(strict_types=1);

namespace Netgen\Bundle\WebSummerCampBundle\Block;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use Netgen\BlockManager\API\Values\Block\Block;
use Netgen\BlockManager\Block\BlockDefinition\BlockDefinitionHandler;
use Netgen\BlockManager\Block\DynamicParameters;
use Netgen\BlockManager\Ez\Parameters\ParameterType as EzParameterType;
use Netgen\BlockManager\Parameters\ParameterBuilderInterface;
use Netgen\BlockManager\Parameters\ParameterType;

final class TagsBlockHandler extends BlockDefinitionHandler
{
    /**
     * @var \eZ\Publish\API\Repository\ContentService
     */
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $builder->add('content', EzParameterType\ContentType::class);
        $builder->add('limit', ParameterType\IntegerType::class, ['default_value' => 3, 'min' => 1]);
    }

    public function getDynamicParameters(DynamicParameters $params, Block $block): void
    {
        if ($block->getParameter('content')->isEmpty()) {
            return;
        }

        try {
            $content = $this->contentService->loadContent(
                $block->getParameter('content')->getValue()
            );
        } catch (NotFoundException | UnauthorizedException $e) {
            return;
        }

        $params['content'] = $content;

        if (!isset($content->fields['tags'])) {
            return;
        }

        $params['tags'] = array_slice(
            $content->getField('tags')->value->tags,
            0,
            $block->getParameter('limit')->getValue()
        );
    }
}
