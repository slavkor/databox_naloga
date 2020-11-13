<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Action;
use Symfony\Component\HttpFoundation\Session\Session;
use Google\Client as Google_Client;
use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

/**
 * Description of Oauth2CallbackAction
 *
 * @author Slavko
 */
class GoogleOauth2CallbackAction {
    private $logger;

    /**
     * @var Session
     */
    private $session;
 
    private $settings;
    
    /**
     *
     * @var Twig
     */
    private $twig;
            
    public function __construct(LoggerInterface $logger,  Session $session, Twig $twig) {
         $this->logger = $logger;
         $this->session = $session;
         $this->twig = $twig;
       
    }
    public function __invoke(ServerRequestInterface $request,ResponseInterface $response ): ResponseInterface {
        $this->logger->info(__CLASS__.':'.__FUNCTION__);
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        
        $client = new Google_Client();
        $client->setAuthConfig("C:\databox\config\google_client_secrets.json");
        $client->setRedirectUri("https://localhost".$routeParser->urlFor(GoogleOauth2CallbackAction::class));
        $client->setAccessType( 'offline' );
        //$client->setApprovalPrompt('consent');

        
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
 
        
        if(!$this->session->has('code')){
            
            $url = $client->createAuthUrl();
            
            return $response->withStatus(307)->withHeader('Location', $url);
        }
        else{
            var_dump($this->session->get('code'));die;
            $client->authenticate($this->session->get('code'));
            $this->session->set('access_token', $client->getAccessToken());
            return  $response->withStatus(307)->withHeader('Location', $routeParser->urlFor(GoogleAction::class));
        }
        
    }
}
