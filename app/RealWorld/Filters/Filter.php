<?php

namespace App\RealWorld\Filters;

use ReflectionClass;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

abstract class Filter
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $collection;

    /**
     * Filter constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get all the available filter methods.
     *
     * @return array
     */
    protected function getFilterMethods()
    {
        $class  = new ReflectionClass(static::class);

        $methods = array_map(function ($method) use ($class) {
            if ($method->class === $class->getName()) {
                return $method->name;
            }
            return null;
        }, $class->getMethods());

        return array_filter($methods);
    }

    /**
     * Get all the filters that can be applied.
     *
     * @return array
     */
    protected function getFilters()
    {
        return array_filter($this->request->only($this->getFilterMethods()));
    }

    /**
     * Apply all the requested filters if available.
     *
     * @param \Jenssegers\Mongodb\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function apply(Builder $builder)
    {
        $this->collection = $builder->latest()->get();

        foreach ($this->getFilters() as $name => $value) {
            if (method_exists($this, $name)) {
                if ($value) {
                    $this->collection = $this->$name($value);
                } else {
                    $this->collection = $this->$name();
                }
            }
        }

        return $this->collection->values();
    }
}
