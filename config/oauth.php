<?php

return [
    
    'google' => [
        
        'api' => [
            
            /**
             * the Google OAuth 2.0 API endpoint for validating token
             * returns email on success
             */
            'verify' => 'https://www.googleapis.com/gmail/v2/users/me/profile',
            
        ]
        
    ],
    
    'internal' => [
        
        'api' => [
            
            /**
             * the Internal OAuth 2.0 API endpoint for validating token
             * returns email on success
             */
            'verify' => 'http://localhost:8000/oauth/token/email',
            
        ]
        
    ]
    
];
