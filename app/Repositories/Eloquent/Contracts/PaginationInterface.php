<?php

namespace App\Repositories\Eloquent\Contracts;

use stdClass;

interface PaginationInterface
{
    /**
     * @return stdClass[]
     */
    public function items(): array;

    /**
     * @return int
     */
    public function total(): int;

    /**
     * @return bool
     */
    public function isFirstPage(): bool;

    /**
     * @return bool
     */
    public function isLastPage(): bool;

    /**
     * @return int
     */
    public function currentPage(): int;

    /**
     * @return int
     */
    public function nextPage(): int;

    /**
     * @return int
     */
    public function previousPage(): int;
}
