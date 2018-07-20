<?php

return [

    'appNameIOS'     => [
        'environment' =>'development',
        'certificate' => public_path().'/apns-dev.pem',
        'passPhrase'  =>'123456',
        'service'     =>'apns'
    ],
    'appNameAndroid' => [
        'environment' =>'production',
        'apiKey'      =>'AIzaSyAsU472n7TtNorj4OkknjpIFaIwMeIkeTs',
        'service'     =>'gcm'
    ]

];
