<?php

return [
    /*
     | Your Google Maps API key, usually set in .env (but see 'keys' section below).
     */

    'key' => env('GOOGLE_MAPS_API_KEY'),

    /*
     | If you need to use both a browser key (restricted by HTTP Referrer) for use in the Javascript API on the
     | front end, and a server key (restricted by IP address) for server side API calls, you will need to set those
     | keys here (or preferably set the appropriate env keys).  You may also set a signing key here for use with
     | static map generation.
     */

    'keys' => [
        'web_key'     => env('FILAMENT_GOOGLE_MAPS_WEB_API_KEY', env('GOOGLE_MAPS_API_KEY')),
        'server_key'  => env('FILAMENT_GOOGLE_MAPS_SERVER_API_KEY', env('GOOGLE_MAPS_API_KEY')),
        'signing_key' => env('FILAMENT_GOOGLE_MAPS_SIGNING_KEY', null),
    ],

    /*
     | By default the browser side Google Maps API will be loaded with just the 'places' library.  If you need
     | additional libraries for your own custom code, just add them as a comma separated list here (or in the
     | appropriate env key). Example: 'geometry,drawing'
     */

    'libraries' => env('FILAMENT_GOOGLE_MAPS_ADDITIONAL_LIBRARIES', ''),

    /*
     | Region and country codes for WishSwipe.
     |
     | Region code set to 'LV' for Latvia to bias searches towards Latvian locations.
     | You can change this to your primary market (US, GB, etc)
     |
     | https://developers.google.com/maps/coverage
     |
     | Language code set to match your app locale for consistent UI.
     */
    'locale' => [
        'region'   => env('FILAMENT_GOOGLE_MAPS_REGION_CODE', 'LV'), // Latvia default
        'language' => env('FILAMENT_GOOGLE_MAPS_LANGUAGE_CODE', 'en'),
        'api'      => env('FILAMENT_GOOGLE_MAPS_API_LANGUAGE_CODE', null),
    ],

    /*
     | Rate limit for API calls. Google's free tier allows 150 requests/minute.
     | IMPORTANT: Also set usage quota limits in your Google Cloud Console!
     */

    'rate-limit' => env('FILAMENT_GOOGLE_MAPS_RATE_LIMIT', 150),

    /*
     | Log channel to use for debugging geocoding issues.
     | Set to 'stack' or 'daily' to enable logging, or leave as 'null' for no logging.
     | Logs are useful for:
     | - Debugging geocoding failures
     | - Monitoring API usage
     | - Tracking scheduled geocoding tasks
     */
    'log' => [
        'channel' => env('FILAMENT_GOOGLE_MAPS_LOG_CHANNEL', 'null'),
    ],

    /*
     | Cache configuration for API results.
     | 
     | Duration: Set to 30 days (max allowed by Google) to minimize API calls
     | Store: null uses default cache, false disables (NOT recommended!), or specify a store
     | 
     | For production with heavy usage, consider using Redis:
     | - Better performance
     | - Persistent caching across deployments
     | - Centralized for multiple servers
     | 
     | Example Redis setup:
     | 'store' => 'redis'
     */

    'cache' => [
        'duration' => env('FILAMENT_GOOGLE_MAPS_CACHE_DURATION_SECONDS', 60 * 60 * 24 * 30), // 30 days
        'store'    => env('FILAMENT_GOOGLE_MAPS_CACHE_STORE', null),
    ],

    /*
    | Force HTTPS for Google API calls.
    | Enable this if your app is behind a reverse proxy or load balancer.
    | This ensures API calls always use HTTPS regardless of incoming request schema.
    */

    'force-https' => env('FILAMENT_GOOGLE_MAPS_FORCE_HTTPS', false),
];