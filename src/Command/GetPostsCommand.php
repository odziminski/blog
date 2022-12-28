<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetPostsCommand extends Command
{
    protected static $defaultName = 'get-posts';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }

    private function getData($url)
    {
        $client = new Client();

        $response = $client->get($url);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }
        return null;
    }


}
