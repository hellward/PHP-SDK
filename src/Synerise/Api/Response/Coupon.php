<?php
namespace Synerise\Api\Response;

class Coupon
{

    /**
     *  Typ zniżki
     * @var string "percent", "cost"
     */
    private $_discount = null;
    
    /**
     * Wartość zniżki
     *
     * @var float
     */
    private $_value = null;

    /**
     *
     * @var string
     */
    private $_name;

    /**
     *
     * @var string
     */
    private $_uuid;

    /**
     *
     * @var string
     */
    private $_type;

    /**
     *
     * @var string
     */
    private $_start;

    /**
     *
     * @var string
     */
    private $_expiration;

    /**
     *
     * @var string
     */
    private $_description;

    /**
     *
     * @var string
     */
    private $_additionalDescription;

    public function __construct($coupon)
    {
        $this->_name = isset($coupon['name']) ? $coupon['name'] : null;
        $this->_uuid = isset($coupon['uuid']) ? $coupon['uuid'] : null;
        $this->_discount = isset($coupon['discount']) ? $coupon['discount'] : null;
        $this->_type = isset($coupon['type']) ? $coupon['type'] : null;
        $this->_value = isset($coupon['value']) ? $coupon['value'] : null;
        $this->_start = isset($coupon['start']) ? $coupon['start'] : null;
        $this->_expiration = isset($coupon['expiration']) ? $coupon['expiration'] : null;
        $this->_description = isset($coupon['description']) ? $coupon['description'] : null;
        $this->_additionalDescription = isset($coupon['additionalDescription']) ? $coupon['additionalDescription'] : null;
    }

    public function getUuid()
    {
        return $this->_uuid;
    }

    public function getDiscount()
    {
        return $this->_discount;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function getAdditionalDescription()
    {
        return $this->_additionadescription;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getValue()
    {
        return (float)$this->_value;
    }

    public function getStart()
    {
        return $this->_start;
    }

    public function getExpiration()
    {
        return $this->_expiration;
    }
}