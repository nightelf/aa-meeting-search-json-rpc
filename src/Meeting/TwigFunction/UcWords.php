<?php


namespace Meeting\TwigFunction;

use Twig_SimpleFilter;

/**
 * Class UcWords
 * @package Meeting\TwigFunction
 */
class UcWords
{
    /**
     * @return array
     */
    public function getFilter()
    {
        return new Twig_SimpleFilter('ucwords', 'ucwords');
    }
}