<?php
namespace Synerise\Api;

use Synerise\SyneriseAbstractHttpClient;
use Synerise\Exception\SyneriseException;
use Synerise\Api\Response\ActiveCoupon;
use Synerise\Api\Response\Coupon;

class CouponClient extends SyneriseAbstractHttpClient
{

    protected $_cache = array();

    /**
     * Get activated coupon details
     * 
     * @param $code
     * @return ActiveCoupon
     * @throws SyneriseException
     */
    public function getActiveCoupon($code)
    {

        try {
            /**
             * @var Response
             */
            if (!isset($this->_cache[$code])) {
                $response = $this->get(SyneriseAbstractHttpClient::BASE_API_URL . '/coupons/active/' . $code);

                $class = 'GuzzleHttp\\Message\\Response';
                if ($response instanceof $class && $response->getStatusCode() == '200') {

                    if ($response->getStatusCode() != '200') {
                        throw new Exception\SyneriseException('API Synerise not responsed 200.', 500);
                    }

                    $json = json_decode($response->getBody(), true);

                    if (isset($json['data']) && $json['data']['coupon']) {
                        $activeCoupon = new ActiveCoupon($json['data']);
                        $this->_cache[$code] = $activeCoupon;
                    }
                } else {
                    throw new SyneriseException('API Synerise not responsed 200.', SyneriseException::API_RESPONSE_ERROR);
                }
            }

            return isset($this->_cache[$code]) ? $this->_cache[$code] : new ActiveCoupon();


        } catch (\Exception $e) {
            if($this->getLogger()) {
                $this->getLogger()->alert($e->getMessage());
            }
            throw $e;
        }

    }

    /**
     * @return Coupons
     * @throws SyneriseException
     */
    public function getCoupons()
    {

        try {
            $response = $this->get(SyneriseAbstractHttpClient::BASE_API_URL . '/admin/coupons/');

            $class = 'GuzzleHttp\\Message\\Response';
            if ($response instanceof $class && $response->getStatusCode() == '200') {
                $collection = array();
                $json = json_decode($response->getBody(), true);
                if(isset($json['data']) && isset($json['data']['coupons'])) {
                    foreach($json['data']['coupons'] as $key => $item) {
                        $collection[$key] = (new Coupon($item));
                    }
                    return $collection;
                } else {
                    throw new SyneriseException('Missing "data" in API resonse.', SyneriseException::API_RESPONSE_INVALID);
                }
                return new Coupon($json);
            } else {
                throw new SyneriseException('API Synerise not responsed 200.', SyneriseException::API_RESPONSE_ERROR);
            }

            return false;

        } catch (\Exception $e) {
            if($this->getLogger()) {
                $this->getLogger()->alert($e->getMessage());
            }
            throw $e;
        }

    }

    /**
     * @param $code
     * @return void
     * @throws SyneriseException
     *         code: 20105 - Coupon.Use.AlreadyUsed
     *         code: -1 - Coupon.UnknownError
     *         code: 500 - HTTP error
     */
    public function useActiveCoupon($code)
    {
        try {
            if (isset($this->_cache[$code])) {
                unset($this->_cache[$code]);
            }

            $response = $this->post(SyneriseAbstractHttpClient::BASE_API_URL . "/coupons/active/$code/use");

            if ($response->getStatusCode() == '200') {
                $responseArray = json_decode($response->getBody(), true);

                switch ($responseArray['code']) {
                    case 1:
                        return true;
                    case 20105:
                        throw new Exception\SyneriseException('Coupon.Use.AlreadyUsed', SyneriseException::COUPON_ALREADY_USED);
                    case 20101:
                        throw new Exception\SyneriseException('Coupon.Use.NotFound', SyneriseException::COUPON_NOT_FOUND);
                    default:
                        throw new Exception\SyneriseException('Coupon.UnknownError', SyneriseException::UNKNOWN_ERROR);
                }
            }
            throw new Exception\SyneriseException('API Synerise not responsed 200.', 500);

        } catch (\Exception $e) {
            if($this->getLogger()) {
                $this->getLogger()->alert($e->getMessage());
            }
            throw $e;
        }
        return false;
    }

    public function activateCoupon($couponUuid, $clientUuid = null)
    {
        try {
            $response = $this->post(SyneriseAbstractHttpClient::BASE_API_URL . "/coupons/$couponUuid/activate");

            if ($response->getStatusCode() != '200') {
                throw new Exception\SyneriseException('API Synerise not responsed 200.', 500);
            }

            $responseArray = json_decode($response->getBody(), true);
            return isset($responseArray['data']) ? $responseArray['data'] : null;

        } catch (\Exception $e) {
            if($this->getLogger()) {
                $this->getLogger()->alert($e->getMessage());
            }
            throw $e;
        }

    }

    public function updateCoupon($couponUuid, $parameters)
    {
        try {
            $response = $this->patch(SyneriseAbstractHttpClient::BASE_API_URL . "/coupons/$couponUuid",  array('json' => $parameters));

            if ($response->getStatusCode() != '200') {
                throw new Exception\SyneriseException('API Synerise not responsed 200.', 500);
            }

            $responseArray = json_decode($response->getBody(), true);
            return isset($responseArray['data']) ? $responseArray['data'] : null;

        } catch (\Exception $e) {
            if($this->getLogger()) {
                $this->getLogger()->alert($e->getMessage());
            }
            throw $e;
        }
    }

}