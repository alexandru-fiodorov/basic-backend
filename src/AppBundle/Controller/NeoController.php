<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NeoController
 *
 * @Route("/neo")
 * @package AppBundle\Controller
 */
class NeoController extends BaseController
{

    /**
     * Displays all potentially hazardous asteroids
     *
     * @Route("/hazardous")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function hazardousAction()
    {
        $asteroids = $this->getDM()
            ->getRepository('AppBundle:Asteroid')
            ->findBy(['hazardous' => true]);

        return $this->json($asteroids);
    }

    /**
     * Get the fastest asteroid
     *
     * @param Request $request
     * @Route("/fastest")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fastestAction(Request $request)
    {
        $hazardous = $request->query->get('hazardous', false);

        if (is_string($hazardous)) {
            $hazardous = strtolower($hazardous) === 'true';
        }

        $asteroid = $this->getDM()
            ->getRepository('AppBundle:Asteroid')
            ->findBy(['hazardous' => $hazardous], ['speed' => -1], 1);

        return $this->json($asteroid);
    }

    /**
     * Get the year with most asteroids
     *
     * @param Request $request
     * @Route("/best-year")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function bestYearAction(Request $request)
    {
        $hazardous = $request->query->get('hazardous', false);

        if (is_string($hazardous)) {
            $hazardous = strtolower($hazardous) === 'true';
        }

        $ab = $this->getDM()
            ->getDocumentCollection('AppBundle:Asteroid')
            ->createAggregationBuilder();
        $ab->match()->field('hazardous')->equals($hazardous);

        $groupBy = ['year' => $ab->expr()->year('$date')];

        $ab->group()
            ->field('_id')->expression($groupBy)
            ->field('asteroids')->sum(1)
            ->sort(['asteroids' => -1])
            ->limit(1);

        $result = $ab->execute()->current();

        return $this->json([
            'year'      => $result['_id']['year'],
            'asteroids' => $result['asteroids']
        ]);
    }

    /**
     * Get the month with most asteroids
     *
     * @param Request $request
     * @Route("/best-month")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function bestMonthAction(Request $request)
    {
        $hazardous = $request->query->get('hazardous', false);

        if (is_string($hazardous)) {
            $hazardous = strtolower($hazardous) === 'true';
        }

        $ab = $this->getDM()
            ->getDocumentCollection('AppBundle:Asteroid')
            ->createAggregationBuilder();
        $ab->match()->field('hazardous')->equals($hazardous);

        $groupBy = ['month' => $ab->expr()->month('$date')];

        $ab->group()
            ->field('_id')->expression($groupBy)
            ->field('asteroids')->sum(1)
            ->sort(['asteroids' => -1])
            ->limit(1);

        $result = $ab->execute()->current();

        return $this->json([
            'month'     => $result['_id']['month'],
            'asteroids' => $result['asteroids']
        ]);
    }

    /**
     * Get the month with most asteroids
     * (couldn't understand should the month be related to year or not so I made both of them)
     *
     * @param Request $request
     * @Route("/best-month-year")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function bestMonthYearAction(Request $request)
    {
        $hazardous = $request->query->get('hazardous', false);

        if (is_string($hazardous)) {
            $hazardous = strtolower($hazardous) === 'true';
        }

        $ab = $this->getDM()
            ->getDocumentCollection('AppBundle:Asteroid')
            ->createAggregationBuilder();
        $ab->match()->field('hazardous')->equals($hazardous);

        $groupBy = [
            'month' => $ab->expr()->month('$date'),
            'year'  => $ab->expr()->year('$date'),
        ];

        $ab->group()
            ->field('_id')->expression($groupBy)
            ->field('asteroids')->sum(1)
            ->sort(['asteroids' => -1])
            ->limit(1);

        $result = $ab->execute()->current();

        return $this->json([
            'month'     => $result['_id']['month'],
            'year'      => $result['_id']['year'],
            'asteroids' => $result['asteroids']
        ]);
    }
}
