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
use App\Domain\Google\GoogleMetrics;
/**
 * Description of PushGoogleMetrics
 *
 * @author Slavko
 */
class GoogleAction {
    private $logger;
    
    /**
     * var GoogleMetrics
     */
    private $google;
    
    /**
     * 
     * @param LoggerInterface $logger
     * @param GoogleMetrics $google
     */
    public function __construct(LoggerInterface $logger, GoogleMetrics $google) {
         $this->logger = $logger;
         $this->google = $google;
    }
    
    public function __invoke(ServerRequestInterface $request,ResponseInterface $response ): ResponseInterface {
        $this->logger->info(__CLASS__.':'.__FUNCTION__);
        /// TODO extract parameters from request
        $data = $this->google->GetGoogleAnalyticsMetrics($request->getParsedBody());
        return $response->withJson(json_encode($data));
    }   
}
