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
use Symfony\Component\HttpFoundation\Session\Session;
use Slim\Routing\RouteContext;
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
     * @var Session
     */
    private $session;
    
    /**
     * 
     * @param LoggerInterface $logger
     * @param GoogleMetrics $google
     */
    public function __construct(LoggerInterface $logger, GoogleMetrics $google, Session $session) {
         $this->logger = $logger;
         $this->google = $google;
         $this->session = $session;
    }
    
    public function __invoke(ServerRequestInterface $request,ResponseInterface $response ): ResponseInterface {
        $this->logger->info(__CLASS__.':'.__FUNCTION__);
        /// TODO extract parameters from request
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                /// TODO extract parameters from request
        $this->session->invalidate();
        $this->session->start();
        
        if($this->session->has('access_token')){
            $data = $this->google->GetGoogleAnalyticsMetrics($request->getParsedBody());
            return $response->withJson(json_encode($data));
        }
        else
        {        
            return $response->withStatus(302)->withHeader('Location', $routeParser->urlFor(GoogleOauth2CallbackAction::class));
        }
        
        
        
    }   
}
