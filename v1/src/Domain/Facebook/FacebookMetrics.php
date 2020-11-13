<?php
namespace App\Domain\Facebook;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Facebook\Facebook;
use Psr\Log\LoggerInterface;
use JsonMapper;
use App\Domain\Model\Metric;
use App\Domain\Model\BasePush;

/**
 * Description of FacebookMetrics
 *
 * @author Slavko
 */
final class FacebookMetrics {
    //put your code here
    private $logger;
    /**
     *
     * @var JsonMapper
     */
    private $jsonMapper;
    public function __construct(LoggerInterface $logger, JsonMapper $jsonMapper) {
         $this->logger = $logger;
         $this->jsonMapper = $jsonMapper;
    }
       
    public function GetPageMetrics($data){
        $this->logger->info(__CLASS__.':'.__FUNCTION__);
  
        //set client 
        $facebook = new Facebook([
            'app_id' =>$data['app_id'],
            'app_secret' => $data['app_secret'],
            'default_graph_version' =>$data['default_graph_version']
        ]);
        
        try {
     
            // get the metrics using sendBatchRequest
            foreach ($data['metrics'] as $key) {
                $requests[] =  $facebook->request('GET','/'.$data['page_id'].'/insights/'.$key.'/day');
            }
            $responses = $facebook->sendBatchRequest($requests, $data['access_token']);
            
       } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
       } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
       }       
       
       //prepare the responses for DataboxPush middleware
       foreach ($responses as $key => $response) {
            if (!$response->isError()) {
                $arr = $response->getGraphEdge()->asArray();
                $metrics[] = new Metric($arr[0]['name'],$arr[0]['values'][0]['value']);
            }
       }

       // return to DataboxPush middleware
       return new BasePush($data['databox_token'], $metrics);
    }
}
