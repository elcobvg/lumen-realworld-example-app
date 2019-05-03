<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Return generic json response with the given data.
     *
     * @param $data
     * @param int $statusCode
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond($data, $statusCode = 200, $headers = [])
    {
        return response($data, $statusCode, $headers);
    }

    /**
     * Respond with success.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondSuccess()
    {
        return $this->respond(null, 204);
    }

    /**
     * Paginate and filter a collection of items
     *
     * @param Collection $collection
     * @param int $offset
     * @return Collection
     */
    protected function paginate(Collection $collection, $offset = 0)
    {
        if (sizeof($collection)) {
            $offset = app('request')->get('offset', $offset);
            $limit = app('request')->get('limit', $collection->first()->getPerPage());

            if (app('request')->has('offset')) {
                $collection = $collection->slice($offset, $limit)->values();
            }
        }
        return $collection;
    }
}
