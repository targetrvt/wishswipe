<?php

return [
    'attributes' => [
        'title' => 'Nosaukums',
        'description' => 'Apraksts',
        'category_id' => 'Kategorija',
        'price' => 'Cena',
        'condition' => 'Stāvoklis',
        'status' => 'Statuss',
        'location' => 'Atrašanās vieta',
        'coordinates' => 'Koordinātas',
        'latitude' => 'Platums',
        'longitude' => 'Garums',
        'images' => 'Attēli',
        'is_active' => 'Aktīvs',
        'is_negotiable' => 'Sarunājams',
    ],
    
    'required' => 'Lauks :attribute ir obligāts.',
    'numeric' => 'Laukam :attribute jābūt skaitlim.',
    'max' => [
        'string' => 'Lauks :attribute nedrīkst būt garāks par :max simboliem.',
        'numeric' => 'Lauks :attribute nedrīkst būt lielāks par :max.',
    ],
    'min' => [
        'numeric' => 'Laukam :attribute jābūt vismaz :min.',
    ],
    'regex' => 'Lauka :attribute formāts nav derīgs.',
];

