<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * BaseController
 */
class BaseController extends Controller
{
    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDM()
    {
        return $this->get('doctrine_mongodb')->getManager();
    }
}
