<?php
/**
 * Created by PhpStorm.
 * User: boda
 * Date: 10.01.14
 * Time: 14:20
 */

namespace HouseFinder\ParserBundle\Service;


use Ivory\GoogleMap\Services\Geocoding\Geocoder;
use Ivory\GoogleMap\Services\Geocoding\GeocoderRequest;
use Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResponse;

class AddressService
{
    /** @var Geocoder */
    protected $geocoder;

    public function __construct(Geocoder $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * @param $address string String representation of address
     * @param string|null $language (optional) Geocoder response language
     * @return GeocoderResponse
     */
    public function getAddress($address, $language = null)
    {
        $request = new GeocoderRequest();
        $request->setAddress($address);
        $request->setLanguage($language);
        return $this->geocoder->geocode($request);
    }
}
