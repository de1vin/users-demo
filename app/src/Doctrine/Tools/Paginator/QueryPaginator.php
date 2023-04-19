<?php

namespace App\Doctrine\Tools\Paginator;

use Closure;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;


/**
 * Class QueryPaginator
 */
readonly class QueryPaginator
{
    /**
     * @param QueryBuilder $qb
     * @param int          $page
     * @param int          $perPage
     * @param Closure|null $recordTransformer
     */
    public function __construct(
        private QueryBuilder $qb,
        private int          $page,
        private int          $perPage,
        private Closure|null $recordTransformer = null
    ){}

    /**
     * @return PaginatedResult
     */
    public function getResult(): PaginatedResult
    {
        $offset = $this->page - 1;
        $offset *= $this->perPage;
        $qb = clone $this->qb;

        $qb->setFirstResult($offset)
            ->setMaxResults($this->perPage);

        $paginator = new Paginator($qb->getQuery());
        $totalRecords = count($paginator);
        $totalPages = ceil($totalRecords / $this->perPage);
        $content = [];

        foreach ($paginator as $record) {
            if ($this->recordTransformer instanceof Closure) {
                $transformer = $this->recordTransformer;
                $content[] = $transformer($record, $this->page, $this->perPage, $totalRecords);
                continue;
            }

            $content[] = $record;
        }

        return new PaginatedResult($content, $this->page, $this->perPage, $totalPages, $totalRecords);
    }
}
