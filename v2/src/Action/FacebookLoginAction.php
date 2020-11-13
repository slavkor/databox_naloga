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

use Slim\Views\Twig;


/**
 * Description of FacebookAction
 *
 * @author Slavko
 */
class FacebookLoginAction {
    private $logger;
    private $facebook;
    /**
     * @var Session
     */
    private $session;
    
    /**
     *
     * @var Twig
     */
    private $twig;
    public function __construct(LoggerInterface $logger, FacebookMetrics $facebook, Session $session, Twig $twig) {
         $this->logger = $logger;
         $this->facebook = $facebook;
         $this->session = $session;
         $this->twig  = $twig;
    }
    public function __invoke(ServerRequestInterface $request,ResponseInterface $response ): ResponseInterface {
        $this->logger->info(__CLASS__.':'.__FUNCTION__);
        $this->session->start();
        
        /// TODO extract parameters from request
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        
        $fb = new Facebook([
            'app_id' =>'1049210122167042',
            'app_secret' => '07e0756579f4f842bf8aad3fefaea723',
            'default_graph_version' => 'v8.0'
        ]);
        
        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl('https://localhost'.$routeParser->urlFor(FacebookGetOauthCallback::class), ['read_insights']);
   
        
        return $this->twig->render($response, 'fb.twig', [ 'loginUrl' => $loginUrl ]);
    }
    
}
