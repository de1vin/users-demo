<?php

namespace App\Doctrine\Tools\Paginator;


/**
 * Class PaginatedResult
 */
readonly class PaginatedResult
{
    /**
     * @param array $content
     * @param int   $page
     * @param int   $perPage
     * @param int   $totalPages
     * @param int   $totalRecords
     */
    public function __construct(
        public array $content,
        public int   $page,
        public int   $perPage,
        public int   $totalPages,
        public int   $totalRecords
    ){}
}
