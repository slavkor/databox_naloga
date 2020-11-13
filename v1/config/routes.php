<?php

use Slim\App;
use App\Domain\Databox\DataboxPush;

return function (App $app) {
    $app->get('/info', function($app){ phpinfo(); });
    $app->group('/databox', function($app){
        
        $app->post('/pushfb', \App\Action\FacebookAction::class)->setName(\App\Action\FacebookAction::class)->add(DataboxPush::class);
        $app->post('/pushgoogle', \App\Action\GoogleAction::class)->setName(\App\Action\GoogleAction::class)->add(DataboxPush::class);
    });
  
};
