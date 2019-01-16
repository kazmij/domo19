<?php

namespace Application\Sonata\MainBundle\Model;

/**
 *
 * @author Tomasz Ulanowski <tomasz@ulanowski.info>
 *
 */
interface RouteableInterface
{
    /**
     * @return string
     */
    public function getRouteName();

    /**
     * @return array
     */
    public function getRouteParams();
}