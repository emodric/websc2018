<?php

declare(strict_types=1);

namespace Netgen\Bundle\WebSummerCampBundle\Query;

use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\FullText;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;
use Netgen\BlockManager\API\Values\Collection\Query;
use Netgen\BlockManager\Collection\QueryType\QueryTypeHandlerInterface;
use Netgen\BlockManager\Parameters\ParameterBuilderInterface;
use Netgen\BlockManager\Parameters\ParameterType;

final class SearchHandler implements QueryTypeHandlerInterface
{
    /**
     * @var \eZ\Publish\API\Repository\SearchService
     */
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $builder->add('text', ParameterType\TextLineType::class);

        $builder->add(
            'sort',
            ParameterType\ChoiceType::class,
            [
                'options' => ['Ascending' => 'asc', 'Descending' => 'desc'],
                'multiple' => false,
            ]
        );
    }

    public function getValues(Query $query, $offset = 0, $limit = null): iterable
    {
        if ($query->getParameter('text')->isEmpty()) {
            return [];
        }

        $eZQuery = $this->getEzQuery($query, $offset, $limit);

        return array_map(
            function (SearchHit $searchHit): Location {
                return $searchHit->valueObject;
            },
            $this->searchService->findLocations($eZQuery)->searchHits
        );
    }

    public function getCount(Query $query): int
    {
        if ($query->getParameter('text')->isEmpty()) {
            return 0;
        }

        $eZQuery = $this->getEzQuery($query, 0, 0);

        return $this->searchService->findLocations($eZQuery)->totalCount;
    }

    public function isContextual(Query $query): bool
    {
        return false;
    }

    private function getEzQuery(Query $query, int $offset, ?int $limit): LocationQuery
    {
        $eZQuery = new LocationQuery();

        $eZQuery->query = new FullText($query->getParameter('text')->getValue());

        $eZQuery->sortClauses = [
            new SortClause\ContentName(
                $query->getParameter('sort')->getValue() === 'desc' ?
                    LocationQuery::SORT_DESC :
                    LocationQuery::SORT_ASC
            ),
        ];

        $eZQuery->offset = $offset;
        $eZQuery->limit = $limit;

        return $eZQuery;
    }
}
