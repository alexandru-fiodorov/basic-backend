<?php

namespace AppBundle\Command;

use AppBundle\Document\Asteroid;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class PopulateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('populate')->setDescription('Populate DB with data from NASA');
    }

    /**
     * Get asteroids from NASA API
     *
     * As asteroid can approach several times to the Earth each time it will be saved as a new DB record
     * To store all approaches for asteroid we need another model "Approaches"
     * related to "Asteroid" as Many to One (many approaches to one asteroid)
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $client    = $this->getContainer()->get('guzzle.client.api_nasa');
        $startDate = date('Y-m-d', strtotime('-3 days'));
        $query     = array_merge(
            $client->getConfig()['query'],
            [
                'start_date' => $startDate,
                'end_date'   => date('Y-m-d'),
            ]
        );
        $response  = $client->get('/neo/rest/v1/feed', ['query' => $query]);

        if ($response->getStatusCode() === 200) {
            $content    = $response->getBody()->getContents();
            $decoder = new JsonDecode(true);
            $data    = $decoder->decode($content, JsonEncoder::FORMAT);
            if ($data['element_count'] > 0) {
                foreach($data['near_earth_objects'] as $date => $objects) {
                    foreach ($objects as $item){
                        $asteroid = new Asteroid();
                        $asteroid->setDate(new \DateTime($date));
                        $asteroid->setHazardous($item['is_potentially_hazardous_asteroid']);
                        $asteroid->setName($item['name']);
                        $asteroid->setReference($item['neo_reference_id']);

                        if (isset($item['close_approach_data'][0])) {
                            $asteroid->setSpeed($item['close_approach_data'][0]['relative_velocity']['kilometers_per_hour']);
                        }

                        $dm->persist($asteroid);
                    }
                }
                $dm->flush();

                $output->writeln("<info>Successfully imported {$data['element_count']} asteroids</info>");
            } else {
                $output->writeln("Successfully imported {$data['element_count']} asteroids");
            }

        } else {
            $output->writeln('<error>There is an error with API, please check</error>');
        }
    }

}
