<?php

namespace App\RealWorld\Transformers;

class TagTransformer extends Transformer
{
    protected $resourceName = 'tags';

    public function transform($data)
    {
        return $data;
    }
}
