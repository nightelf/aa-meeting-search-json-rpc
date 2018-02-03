<?php

namespace Meeting\TwigFunction;

use Exception;
use Location\Coordinate;
use Location\Distance\Vincenty;
use Meeting\Address;
use number_format;
use Twig_SimpleFunction;

/**
 * Class GetDistance
 */
class GetDistance {

    /**
     * @var string
     */
    const METERS_PER_MILE = 1609.344;

    /**
     * @return Twig_SimpleFunction
     */
    public function getFunction() : Twig_SimpleFunction {

        return new Twig_SimpleFunction('get_distance', function (Address $address1, Address $address2 = null) {

            $calculator = new Vincenty();
            try {
                $coordinate1 = new Coordinate($address1->getLat(), $address1->getLng());
                $coordinate2 = new Coordinate($address2->getLat(), $address2->getLng());
            } catch (Exception $e) {
                return null;
            }

            return number_format($calculator->getDistance($coordinate1, $coordinate2) / self::METERS_PER_MILE, 1);
        });
    }
}