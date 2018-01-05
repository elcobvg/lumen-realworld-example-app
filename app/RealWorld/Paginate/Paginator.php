<?php

namespace App\RealWorld\Paginate;

use Illuminate\Database\Eloquent\Collection;

class Paginator
{
    /**
     * Total count of the items.
     *
     * @var int
     */
    protected $total;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $collection;

    /**
     * Paginate constructor.
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param int $page
     */
    public function __construct(Collection $collection, $offset = 0)
    {
        $offset = app('request')->get('offset', $offset);
        $limit = app('request')->get('limit', $collection->first()->getPerPage());
        $this->total = $collection->count();

        if (app('request')->has('offset')) {
            $collection = $collection->slice($offset, $limit)->values();
        }
        $this->collection = $collection;
    }

    /**
     * Get the total count of the items.
     *
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->total;
    }

    /**
     * Get the paginated collection of items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return $this->collection;
    }
}
