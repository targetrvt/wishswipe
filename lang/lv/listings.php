<?php

return [
    'navigation_label' => 'Mani Sludinājumi',
    'page_title' => 'Mani Sludinājumi',
    
    'columns' => [
        'image' => 'Attēls',
        'title' => 'Nosaukums',
        'category' => 'Kategorija',
        'price' => 'Cena',
        'condition' => 'Stāvoklis',
        'status' => 'Statuss',
        'views' => 'Skatījumi',
        'total_swipes' => 'Kopā Velk',
        'likes' => 'Patīk',
        'active' => 'Aktīvs',
        'negotiable' => 'Sarunājams',
        'created_at' => 'Izveidots',
    ],
    
    'condition' => [
        'new' => 'Jauns',
        'like_new' => 'Kā Jauns',
        'used' => 'Lietots',
    ],
    
    'status' => [
        'available' => 'Pieejams',
        'reserved' => 'Rezervēts',
        'sold' => 'Pārdots',
    ],
    
    'filters' => [
        'status' => 'Statuss',
        'category' => 'Kategorija',
        'active' => 'Aktīvs',
    ],
    
    'actions' => [
        'view' => 'Skatīt',
        'view_map' => 'Skatīt Kartē',
        'edit' => 'Labot',
        'mark_sold' => 'Atzīmēt kā Pārdots',
        'mark_available' => 'Atzīmēt kā Pieejams',
        'delete' => 'Dzēst',
    ],
    
    'form' => [
        'title' => 'Nosaukums',
        'description' => 'Apraksts',
        'category' => 'Kategorija',
        'price' => 'Cena',
        'condition' => 'Stāvoklis',
        'status' => 'Statuss',
        'location' => 'Atrašanās vieta',
        'location_placeholder' => 'Meklēt atrašanās vietu...',
        'map' => 'Karte',
        'images' => 'Attēli',
        'active' => 'Aktīvs',
        'negotiable' => 'Sarunājams',
    ],
    
    'bulk_actions' => [
        'delete' => 'Dzēst',
        'mark_available' => 'Atzīmēt kā Pieejams',
        'mark_sold' => 'Atzīmēt kā Pārdots',
    ],
    
    'empty_state' => [
        'create_first' => 'Izveidot savu pirmo sludinājumu',
        'no_listings' => 'Nav Sludinājumu',
        'no_listings_description' => 'Izveidojiet savu pirmo sludinājumu, lai sāktu pārdot WishSwipe',
        'create_first_listing' => 'Izveidot Pirmo Sludinājumu',
        'start_selling_description' => 'Sāciet pārdot, izveidojot savu pirmo produktu',
    ],

    'stats' => [
        'total' => 'Kopā',
        'active' => 'Aktīvi',
        'sold' => 'Pārdoti',
        'views' => 'Skatījumi',
        'revenue' => 'Ieņēmumi',
    ],

    'performance' => [
        'title' => 'Veiktspējas Rādītāji',
        'active_rate' => 'Aktīvais Līmenis',
        'sold_rate' => 'Pārdošanas Līmenis',
        'avg_views' => 'Vid. Skatījumi',
        'per_item' => 'uz preci',
    ],

    'all_listings' => 'Visi Sludinājumi',

    'tips' => [
        'quality_photos' => [
            'title' => 'Kvalitatīvi Foto',
            'description' => 'Izmantojiet skaidrus, labi apgaismotus foto no vairākiem leņķiem',
        ],
        'detailed_description' => [
            'title' => 'Detalizēts Apraksts',
            'description' => 'Iekļaujiet stāvokli, īpašības un specifikācijas',
        ],
        'fair_pricing' => [
            'title' => 'Godīga Cena',
            'description' => 'Izpētiet līdzīgas preces un cenojiet konkurētspējīgi',
        ],
        'quick_response' => [
            'title' => 'Ātra Atbilde',
            'description' => 'Atbildiet uz ziņojumiem 24 stundu laikā',
        ],
    ],

    'new_listing' => 'Jauns Sludinājums',

    'modal' => [
        'images' => 'Attēli',
        'price' => 'Cena',
        'category' => 'Kategorija',
        'condition' => 'Stāvoklis',
        'status' => 'Statuss',
        'location' => 'Atrašanās vieta',
        'views' => 'Skatījumi',
        'description' => 'Apraksts',
        'created' => 'Izveidots',
        'created_at_label' => 'Izveidots: :date',
        'uncategorized' => 'Bez kategorijas',
    ],

    'filament' => [
        'product_information' => 'Produkta Informācija',
        'product_information_description' => 'Ievadiet pamatinformāciju par savu produktu',
        'title_placeholder' => 'piem., iPhone 13 Pro Max',
        'description_placeholder' => 'Sniegt detalizētu aprakstu par savu produktu...',
        'description_helper' => 'Esiet specifisks par stāvokli, īpašībām un defektiem',
        'price_placeholder' => '0.00',
        'price_helper' => 'Ievadiet cenu EUR',
        'condition_options' => [
            'new' => 'Jauns',
            'like_new' => 'Kā Jauns',
            'used' => 'Lietots',
        ],
        'status_options' => [
            'available' => 'Pieejams',
            'reserved' => 'Rezervēts',
            'sold' => 'Pārdots',
        ],
        'location_placeholder' => 'Meklēt atrašanās vietu...',
        'location_helper' => 'Ievadiet atrašanās vietu, kur prece ir pieejama',
        'location_search_helper' => 'Ierakstiet atrašanās vietu, lai meklētu, vai noklikšķiniet uz kartes zemāk, lai automātiski aizpildītu',
        'map_label' => 'Karte',
        'images_section' => 'Produkta Attēli',
        'images_description' => 'Augšupielādējiet līdz 10 augstas kvalitātes attēlus savam produktam',
        'images_helper' => 'Pirmais attēls tiks izmantots kā galvenais foto',
        'visibility_section' => 'Redzamības Iestatījumi',
        'visibility_description' => 'Kontrolējiet, kā jūsu produkts parādās citiem lietotājiem',
        'active_helper' => 'Neaktīvi produkti netiks rādīti citiem lietotājiem',
        'negotiable_helper' => 'Atļauj citiem lietotājiem sarunāt cenu',
        'geolocate_label' => '📍 Iegūt Manu Atrašanās Vietu',
        'map_helper' => 'Noklikšķiniet uz kartes, lai iestatītu savu atrašanās vietu, vai izmantojiet meklēšanas lodziņu un pogu "Iegūt Manu Atrašanās Vietu"',
        'latitude_label' => 'Platums',
        'longitude_label' => 'Garums',
        'latitude_placeholder' => '56.9496',
        'longitude_placeholder' => '24.1052',
        'coordinates_helper' => 'Automātiski iestatīts, izmantojot karti',
        'created_label' => 'Izveidots',
        'total_views_label' => 'Kopā Skatījumi',
        'image_label' => 'Attēls',
        'active_label' => 'Aktīvs',
        'views_label' => 'Skatījumi',
        'has_images_label' => 'Ir Attēli',
        
        // Table Actions
        'mark_sold_label' => 'Atzīmēt kā Pārdots',
        'mark_available_label' => 'Atzīmēt kā Pieejams',
        'toggle_active_label' => 'Pārslēgt Aktīvumu',
        'activate_label' => 'Aktivizēt',
        'deactivate_label' => 'Deaktivizēt',
        'actions_label' => 'Darbības',
        
        // Bulk Actions
        'bulk_mark_available_label' => 'Atzīmēt kā Pieejams',
        'bulk_mark_sold_label' => 'Atzīmēt kā Pārdots',
        'bulk_activate_label' => 'Aktivizēt',
        'bulk_deactivate_label' => 'Deaktivizēt',
        
        // Notifications
        'product_created' => 'Produkts veiksmīgi izveidots',
        'product_marked_sold' => 'Produkts atzīmēts kā pārdots',
        'product_marked_available' => 'Produkts atzīmēts kā pieejams',
        'product_updated' => 'Produkts veiksmīgi atjaunināts',
        
        // Empty State
        'create_first_product' => 'Izveidot savu pirmo produktu',
        
        // Confirmation Modals
        'confirm' => 'Apstiprināt',
        'cancel' => 'Atcelt',
        'close' => 'Aizvērt',
        'view_product_heading' => 'Produkta Detaļas',
        'mark_sold_confirm_heading' => 'Atzīmēt kā Pārdots',
        'mark_sold_confirm_description' => 'Vai tiešām vēlaties atzīmēt šo produktu kā pārdotu?',
        'mark_available_confirm_heading' => 'Atzīmēt kā Pieejams',
        'mark_available_confirm_description' => 'Vai tiešām vēlaties atzīmēt šo produktu kā pieejamu?',
        'activate_confirm_heading' => 'Aktivizēt Produktu',
        'activate_confirm_description' => 'Vai tiešām vēlaties aktivizēt šo produktu?',
        'deactivate_confirm_heading' => 'Deaktivizēt Produktu',
        'deactivate_confirm_description' => 'Vai tiešām vēlaties deaktivizēt šo produktu?',
        'delete_confirm_heading' => 'Dzēst Produktu',
        'delete_confirm_description' => 'Vai tiešām vēlaties dzēst šo produktu? Šo darbību nevar atsaukt.',
        
        // Bulk Action Confirmations
        'bulk_mark_available_confirm_heading' => 'Atzīmēt kā Pieejams',
        'bulk_mark_available_confirm_description' => 'Vai tiešām vēlaties atzīmēt izvēlētos produktus kā pieejamus?',
        'bulk_mark_sold_confirm_heading' => 'Atzīmēt kā Pārdots',
        'bulk_mark_sold_confirm_description' => 'Vai tiešām vēlaties atzīmēt izvēlētos produktus kā pārdotus?',
        'bulk_activate_confirm_heading' => 'Aktivizēt Produktus',
        'bulk_activate_confirm_description' => 'Vai tiešām vēlaties aktivizēt izvēlētos produktus?',
        'bulk_deactivate_confirm_heading' => 'Deaktivizēt Produktus',
        'bulk_deactivate_confirm_description' => 'Vai tiešām vēlaties deaktivizēt izvēlētos produktus?',
        'bulk_delete_confirm_heading' => 'Dzēst Produktus',
        'bulk_delete_confirm_description' => 'Vai tiešām vēlaties dzēst izvēlētos produktus? Šo darbību nevar atsaukt.',
    ],
];