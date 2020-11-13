<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Action;
use Symfony\Component\HttpFoundation\Session\Session;

use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Facebook\Facebook;

use App\Domain\Facebook\FacebookMetrics;
use App\Domain\Facebook\TokenRepository;
use Slim\Views\Twig;
/**
 * Description of FacebookGetOauthCallback
 *
 * @author Slavko
 */
class FacebookGetOauthCallback {
 private $logger;

    /**
     * @var Session
     */
    private $session;
 
    private $settings;
    
    /**
     *
     * @var FacebookMetrics
     */
    private $facebook;
    

    /**
     * var Twig
     */
    private $twig;
    
    public function __construct(LoggerInterface $logger,  Session $session,  FacebookMetrics $facebook, Twig $twig) {
         $this->logger = $logger;
         $this->session = $session;
         $this->facebook = $facebook;
         $this->twig = $twig;
    }
    public function __invoke(ServerRequestInterface $request,ResponseInterface $response ): ResponseInterface {
        $this->logger->info(__CLASS__.':'.__FUNCTION__);
        
        $data = $this->facebook->GetAccessToken();
        
        $this->session->set('access_token', $data['access_token']);
        $this->session->set('page_id', $data['page_id']);
        
        if (isset($data['access_token'])) {
      
            return $this->twig->render($response, 'fbpost.twig', [ 'access_token' => $data['access_token'], 
                'page_id'=>  $data['page_id'], 
                'metrics'=> 'page_views_total,page_engaged_users,page_actions_post_reactions_like_total,page_total_actions,page_consumptions' ]);
        } else {
            return $response->withStatus(500);
        }
    }
}
