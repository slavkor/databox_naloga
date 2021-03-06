<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Action;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Log\LoggerInterface;
use App\Domain\Facebook\FacebookMetrics;

/**
 * Description of FacebookAction
 *
 * @author Slavko
 */
class FacebookAction {
    private $logger;
    private $facebook;
    
    public function __construct(LoggerInterface $logger, FacebookMetrics $facebook) {
         $this->logger = $logger;
         $this->facebook = $facebook;
    }
    public function __invoke(ServerRequestInterface $request,ResponseInterface $response ): ResponseInterface {
        $this->logger->info(__CLASS__.':'.__FUNCTION__);
        /// TODO extract parameters from request
        
        $data = $this->facebook->GetPageMetrics($request->getParsedBody());
     
        return $response->withJson(json_encode($data));
    }
}
