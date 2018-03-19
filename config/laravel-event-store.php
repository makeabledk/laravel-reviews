<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Events to record to database
    |--------------------------------------------------------------------------
    |
    | These events will be recorded and stored in the 'events' table in database
    |
    | Wildcards are supported.
    */

    'log' => [
        'App\Events\*',
        'Laravel\Spark\Events\*',
        'Services\*',
    ],

];
