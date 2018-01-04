<?php

namespace App\RealWorld\Paginate;

use Jenssegers\Mongodb\Eloquent\Builder;

class Paginate
{
    /**
     * Total count of the items.
     *
     * @var int
     */
    protected $total;

    /**
     * Collection of items.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $data;

    /**
     * Paginate constructor.
     *
     * @param \Jenssegers\Mongodb\Eloquent\Builder $builder
     * @param int $limit
     * @param int $offset
     */
    public function __construct(Builder $builder, $limit = 20, $offset = 0)
    {
        $limit = app('request')->get('limit', $limit);

        $offset = app('request')->get('offset', $offset);

        $this->total = $builder->count();

        $this->data = $builder->latest()->skip($offset)->take($limit)->get();
    }

    /**
     * Get the total count of the items.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get the paginated collection of items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData()
    {
        return $this->data;
    }
}
