<?php

return [
    'navigation_label' => 'My Listings',
    'page_title' => 'My Listings',
    
    'columns' => [
        'image' => 'Image',
        'title' => 'Title',
        'category' => 'Category',
        'price' => 'Price',
        'condition' => 'Condition',
        'status' => 'Status',
        'views' => 'Views',
        'total_swipes' => 'Total Swipes',
        'likes' => 'Likes',
        'active' => 'Active',
        'negotiable' => 'Negotiable',
        'created_at' => 'Created At',
    ],
    
    'condition' => [
        'new' => 'New',
        'like_new' => 'Like New',
        'used' => 'Used',
    ],
    
    'status' => [
        'available' => 'Available',
        'reserved' => 'Reserved',
        'sold' => 'Sold',
    ],
    
    'filters' => [
        'status' => 'Status',
        'category' => 'Category',
        'active' => 'Active',
    ],
    
    'actions' => [
        'view' => 'View',
        'view_map' => 'View on Map',
        'edit' => 'Edit',
        'mark_sold' => 'Mark as Sold',
        'mark_available' => 'Mark as Available',
        'delete' => 'Delete',
    ],
    
    'form' => [
        'title' => 'Title',
        'description' => 'Description',
        'category' => 'Category',
        'price' => 'Price',
        'condition' => 'Condition',
        'status' => 'Status',
        'location' => 'Location',
        'location_placeholder' => 'Search for a location...',
        'map' => 'Map',
        'images' => 'Images',
        'active' => 'Active',
        'negotiable' => 'Negotiable',
    ],
    
    'bulk_actions' => [
        'delete' => 'Delete',
        'mark_available' => 'Mark as Available',
        'mark_sold' => 'Mark as Sold',
    ],
    
    'empty_state' => [
        'create_first' => 'Create your first listing',
        'no_listings' => 'No Listings Yet',
        'no_listings_description' => 'Create your first listing to start selling on WishSwipe',
        'create_first_listing' => 'Create First Listing',
        'start_selling_description' => 'Start selling by creating your first product',
    ],

    'stats' => [
        'total' => 'Total',
        'active' => 'Active',
        'sold' => 'Sold',
        'views' => 'Views',
        'revenue' => 'Revenue',
    ],

    'performance' => [
        'title' => 'Performance Metrics',
        'active_rate' => 'Active Rate',
        'sold_rate' => 'Sold Rate',
        'avg_views' => 'Avg. Views',
        'per_item' => 'per item',
    ],

    'all_listings' => 'All Listings',

    'tips' => [
        'quality_photos' => [
            'title' => 'Quality Photos',
            'description' => 'Use clear, well-lit photos from multiple angles',
        ],
        'detailed_description' => [
            'title' => 'Detailed Description',
            'description' => 'Include condition, features, and specifications',
        ],
        'fair_pricing' => [
            'title' => 'Fair Pricing',
            'description' => 'Research similar items and price competitively',
        ],
        'quick_response' => [
            'title' => 'Quick Response',
            'description' => 'Reply to messages within 24 hours',
        ],
    ],

    'new_listing' => 'New Listing',

    'modal' => [
        'images' => 'Images',
        'price' => 'Price',
        'category' => 'Category',
        'condition' => 'Condition',
        'status' => 'Status',
        'location' => 'Location',
        'views' => 'Views',
        'description' => 'Description',
        'created' => 'Created',
        'uncategorized' => 'Uncategorized',
    ],

    'filament' => [
        'product_information' => 'Product Information',
        'product_information_description' => 'Enter the basic information about your product',
        'title_placeholder' => 'e.g., iPhone 13 Pro Max',
        'description_placeholder' => 'Provide a detailed description of your product...',
        'description_helper' => 'Be specific about condition, features, and any defects',
        'price_placeholder' => '0.00',
        'price_helper' => 'Enter the price in EUR',
        'condition_options' => [
            'new' => 'New',
            'like_new' => 'Like New',
            'used' => 'Used',
        ],
        'status_options' => [
            'available' => 'Available',
            'reserved' => 'Reserved',
            'sold' => 'Sold',
        ],
        'location_placeholder' => 'Search for a location...',
        'location_helper' => 'Enter the location where the item is available',
        'images_section' => 'Product Images',
        'images_description' => 'Upload up to 10 high-quality images of your product',
        'images_helper' => 'First image will be used as the main photo',
        'visibility_section' => 'Visibility Settings',
        'visibility_description' => 'Control how your product appears to other users',
        'active_helper' => 'Inactive products will not be shown to other users',
        'negotiable_helper' => 'Allow other users to negotiate the price',
        'geolocate_label' => '📍 Get My Location',
        'map_helper' => 'Click on the map to set your location, or use the search box and "Get My Location" button',
        'latitude_label' => 'Latitude',
        'longitude_label' => 'Longitude',
        'latitude_placeholder' => '56.9496',
        'longitude_placeholder' => '24.1052',
        'coordinates_helper' => 'Automatically set when using map',
        'created_label' => 'Created',
        'total_views_label' => 'Total Views',
        'image_label' => 'Image',
        'active_label' => 'Active',
        'views_label' => 'Views',
        'has_images_label' => 'Has Images',
        
        // Table Actions
        'mark_sold_label' => 'Mark as Sold',
        'mark_available_label' => 'Mark as Available',
        'toggle_active_label' => 'Toggle Active',
        'activate_label' => 'Activate',
        'deactivate_label' => 'Deactivate',
        'actions_label' => 'Actions',
        
        // Bulk Actions
        'bulk_mark_available_label' => 'Mark as Available',
        'bulk_mark_sold_label' => 'Mark as Sold',
        'bulk_activate_label' => 'Activate',
        'bulk_deactivate_label' => 'Deactivate',
        
        // Notifications
        'product_marked_sold' => 'Product marked as sold',
        'product_marked_available' => 'Product marked as available',
        'product_updated' => 'Product updated',
        
        // Empty State
        'create_first_product' => 'Create your first product',
    ],
];