<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace BaseBundle\Library;

use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class TraitWorkflowUtility
 * @package BaseBundle\Library
 */
trait TraitWorkflowUtility{
    
    public function  generatePaginateCollection($qb, string $route, array $sort = [], array $filter = [], int $page = 1, int $limit = 25, array $extraParams = []): PaginatedRepresentation{


        $pagerAdapter = new DoctrineORMAdapter($qb);

        $pager = new Pagerfanta($pagerAdapter);

        $pager->setMaxPerPage($limit);

        $pager->setCurrentPage($page);
        
        $pagerFactory = new PagerfantaFactory();


        $routeParam = [
            'limit' => $limit,
            'page' => $pager,
            'sort' => $sort,
            'filter' => $filter
        ];

        if (0 != count($extraParams)) {
            $routeParam = array_merge($routeParam, $extraParams);
        }


        return $pagerFactory->createRepresentation($pager, new Route($route, $routeParam));
    }
}