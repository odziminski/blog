<?php

namespace App\Command;

use App\Entity\Post;
use App\Entity\Author;
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
        $this->entityManager = $entityManager;
        $this->authorRepository = $this->entityManager->getRepository(Author::class);
        $this->postRepository = $this->entityManager->getRepository(Post::class);

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $authors = $this->getData('https://jsonplaceholder.typicode.com/users');

        if ($authors) {
            foreach ($authors as $author) {
                $existingAuthor = $this->authorRepository->findOneBy(['id' => $author['id']]);
                if (!$existingAuthor) {
                    $this->insertauthorData($author);
                }
            }
            $this->entityManager->flush();

        }
        $posts = $this->getData('https://jsonplaceholder.typicode.com/posts');

        if ($posts) {
            foreach ($posts as $post) {
                $existingPost = $this->postRepository->findOneBy([
                    'title' => $post['title'],
                    'body' => $post['body'],
                ]);

                if (!$existingPost) {
                    $this->insertPostData($post);
                }
            }

            $this->entityManager->flush();
            $output->writeln('Data has been retrieved and saved to database');
            return 0;

        } else {
            $output->writeln('An error ocurred while trying to get data');
            return 1;
        }
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

    private function insertPostData($post)
    {
        $postEntity = new Post();
        $postEntity->setauthorId($post['userId']);
        $postEntity->setId($post['id']);
        $postEntity->setTitle($post['title']);
        $postEntity->setBody($post['body']);

        $this->entityManager->persist($postEntity);
    }

    private function insertAuthorData($author)
    {

        $authorEntity = new Author();
        $authorEntity->setId($author['id']);
        $authorEntity->setName($author['name']);
        $authorEntity->setUsername($author['username']);

        $this->entityManager->persist($authorEntity);
    }
}
