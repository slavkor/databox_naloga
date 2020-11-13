<?php

use Slim\App;
use App\Domain\Databox\DataboxPush;

return function (App $app) {
    $app->get('/info', function($app){ phpinfo(); });
   
        $app->group('/fb', function($app){
            $app->get('/foa2cb', \App\Action\FacebookGetOauthCallback::class)->setName(\App\Action\FacebookGetOauthCallback::class);
            $app->post('/pushfb', \App\Action\FacebookAction::class)->setName('fbpost')->add(DataboxPush::class);
            $app->get('/start', \App\Action\FacebookLoginAction::class)->setName(\App\Action\FacebookLoginAction::class);
        });
        
        $app->group('/google', function($app){
            $app->post('/start', \App\Action\GoogleAction::class)->setName(\App\Action\GoogleAction::class);//->add(DataboxPush::class);
            $app->get('/goa2cb', \App\Action\GoogleOauth2CallbackAction::class)->setName(\App\Action\GoogleOauth2CallbackAction::class);
            
        });
};


    
    