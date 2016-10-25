<?php
namespace Synerise\Api;

use GuzzleHttp\Exception\RequestException;
use Synerise\Exception\SyneriseException;
use Synerise\Response\Newsletter as SyneriseResponseNewsletter;

class NewsletterClient extends SyneriseAbstractHttpClient
{
    
    public function subscribe($email, $additionalParams = array())
    {

        try {
            $baseParams = array();
            $baseParams['email'] = $email;
            if(!empty($this->getUuid())){
                $baseParams['uuid'] = $this->getUuid();
            }

            if(isset($additionalParams['sex'])) { //@todo
                $baseParams['sex '] = $additionalParams['sex'];
            }

            try {
                
                $response = $this->put(SyneriseAbstractHttpClient::BASE_API_URL . "/client/subscribe",
                    array('json' => array_merge($baseParams, array('params' => $additionalParams)))
                );

                $responseArray = json_decode($response->getBody(), true);
                $responseNewsletter = new SyneriseResponseNewsletter($responseArray);
                return $responseNewsletter->success();

            } catch (RequestException $e) {
                $responseArray = json_decode($e->getResponse()->getBody(), true);
                $responseNewsletter = new SyneriseResponseNewsletter($responseArray);
                return $responseNewsletter->fail();
            }
            
            throw new SyneriseException('Unknown error', SyneriseException::UNKNOWN_ERROR);

        } catch (\Exception $e) {
            if($this->getLogger()) {
                $this->getLogger()->alert($e->getMessage());
            }
            throw $e;
        }

    }

}