<?php

return [
    'attributes' => [
        'title' => 'Title',
        'description' => 'Description',
        'category_id' => 'Category',
        'price' => 'Price',
        'condition' => 'Condition',
        'status' => 'Status',
        'location' => 'Location',
        'coordinates' => 'Coordinates',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'images' => 'Images',
        'is_active' => 'Active',
        'is_negotiable' => 'Negotiable',
    ],
    
    'required' => 'The :attribute field is required.',
    'numeric' => 'The :attribute must be a number.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
        'numeric' => 'The :attribute may not be greater than :max.',
    ],
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
    ],
    'regex' => 'The :attribute format is invalid.',
];

