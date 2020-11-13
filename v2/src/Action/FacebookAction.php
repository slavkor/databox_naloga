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
use Symfony\Component\HttpFoundation\Session\Session;
use Slim\Routing\RouteContext;
use App\Action\FacebookGetOauthCallback;
use Facebook\Facebook;

/**
 * Description of FacebookAction
 *
 * @author Slavko
 */
class FacebookAction {
    private $logger;
    private $facebook;
    /**
     * @var Session
     */
    private $session;
    public function __construct(LoggerInterface $logger, FacebookMetrics $facebook, Session $session) {
         $this->logger = $logger;
         $this->facebook = $facebook;
         $this->session = $session;
    }
    public function __invoke(ServerRequestInterface $request,ResponseInterface $response ): ResponseInterface {
        $this->logger->info(__CLASS__.':'.__FUNCTION__);
        
        $data = $this->facebook->GetPageMetrics($request->getParsedBody(), $this->session->get('access_token'));
        return $response->withJson(json_encode($data));
       
        
    }
}
