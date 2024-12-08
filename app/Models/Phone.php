<?php

namespace App\Models;


class Phone
{
    
    /**
     * Country Dial Code
     */
    public $countryCode;
    
    /**
     * Region code
     */
    public $regionCode;

    /**
     * Middle part of device number
     */
    public $middlePart;

    /**
     * Last part of device number
     */
    public $lastPart;

    public function __construct($phoneNumber = null, $conver9Dig = true, $defaultCountryCode = "55", $defaultRegionCode = "21")
    {
        if($phoneNumber)
        {
            $this->setPhoneNumber($phoneNumber, $conver9Dig, $defaultCountryCode, $defaultRegionCode);
        }
    }

    public function setPhoneNumber($phone, $conver9Dig = true, $defaultCountryCode = "55", $defaultRegionCode = "21")
    {
        if($phone === false && is_null($phone) && trim($phone) === '') {
            return null;
        }
        
        $phone = trim($phone);
        $phone = preg_replace('/[^0-9.]/', '', $phone);
        $count = strlen($phone);
    
        //nine digits
        $ninedig = in_array($count, [9, 11, 13]);
    
        //Is public utilities service
        $isSUP = in_array($count, range(3, 5)) && in_array(substr(substr($phone, -5), -5, 1), ['1', '2']);
    
        //Is public utility service extension
        $isSUPExt = $count <= 5 && in_array(substr(substr($phone, -5), -3, 1), ['3', '5', '6']);
    
        //Is Public Switched Telephony Network
        $isSTFC = !$isSUP && in_array(substr(substr($phone, -8), -8, 1), ['2', '3', '4', '5']);
        
        //is Special mobile service via radio (nextel)
        $isSME = !$ninedig && !$isSUP && in_array(substr(substr($phone, -8), -8, 2), ['70', '77', '78', '79']);    
    
        //is Cellular Mobile Service (like at&t, claro)
        $isSMC = !$isSTFC && !$isSME;
    
        $phonePad = str_pad($phone, 12, '0', STR_PAD_LEFT);
    
        //parse if is SMC without 9digit
        if($isSMC && substr($phone, -9, 1) !== '9') {
            $phonePad = substr($phonePad, -12, 4)."9".substr($phone, -8);
        }else if($isSMC) {
            $phonePad = str_pad($phonePad, 13, '0', STR_PAD_LEFT);
        }
    
        //get first part of device number
        if($isSMC) {
            $this->middlePart = substr($phonePad, -9, 5);
        }else {
            $this->middlePart = substr($phonePad, -8, 4);
        }
    
        //get last part of device number
        $this->lastPart = substr($phone, -4);
    
        //get country code or 00
        $this->countryCode = substr($phonePad, 0, 2) !== '00' ? substr($phonePad, 0, 2) : $defaultCountryCode;
        
        //get local code or 00
        $this->regionCode = substr($phonePad, 2, 2) !== '00' ? substr($phonePad, 2, 2) : $defaultRegionCode;

        return $this;
    }

    public function isValid()
    {
        return strlen($this->globalNumber()) >= 12;
    }

    /**
     * Retorna o telefone no padrao internacional
     * 
     * @return string
     */
    public function globalNumber()
    {
        return "{$this->countryCode}{$this->regionCode}{$this->middlePart}{$this->lastPart}";
    }

    /**
     * Retorna o telefone no padrao local DDD + Num
     * 
     * @return string
     */
    public function localNumber()
    {
        return "{$this->regionCode}{$this->middlePart}{$this->lastPart}";
    }

    /**
     * Retorna o código do país
     * 
     * @return string
     */
    public function getDialCode()
    {
        return substr($this->globalNumber(), 0, 2);
    }

    /**
     * Retorna o país
     * 
     * @return array
     */
    public function getCountry()
    {
        return $this->getCountryList()->firstWhere('dialCode', '=', '+'. $this->getDialCode());
    }

    /**
     * Retorna código ISO o país
     * 
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getCountry()['isoCode'];
    }

    /**
     * Retorna a lista de países
     * 
     * @return Collection
     */
    protected function getCountryList()
    {
        return collect(json_decode(file_get_contents(storage_path('app/countries.json')), true));
    }
}
