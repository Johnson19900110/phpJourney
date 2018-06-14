<?php

namespace App\Libraries;

trait EsSearchable
{
    public $searchSettings = [
        'attributesToHighlight' => [
            '*'
        ]
    ];

    public $highlight = [];
}