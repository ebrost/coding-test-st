<?php

namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Question;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class ApiTest extends ApiTestCase
{

    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/questions');
        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains(
            [
                '@context' => '/api/contexts/Question',
                '@id' => '/api/questions/1',
                '@type' => 'Question',
                '@context' => '/contexts/Question',
                '@id' => '/questions',
                '@type' => 'hydra:Collection',
            ]
        );
        // 5 question created with fixtures and reloaded thanks to the RefreshDataBaseTrait trait
        $this->assertCount(5, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Question::class);
    }

    public function testCreateQuestion(): void
    {
        $response = static::createClient()->request(
            'POST',
            '/questions',['json' => [
                    'title' => 'next Question',
                    'promoted' => true,
                    'status' => 'draft',
                    'answers' => [
                        [
                            'channel' => 'bot',
                            'body' => 'first response to this question'
                        ],
                        [
                            'channel' => 'faq',
                            'body' => 'same response to this question but for faq'
                        ]
                    ]
         ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            "@context"=> "/contexts/Question",
            "@type"=> "Question",
            'title' => 'next Question',
            'promoted' => true,
            'status' => 'draft',
            'answers' => [
                [
                    'channel' => 'bot',
                    'body' => 'first response to this question'
                ],
                [
                    'channel' => 'faq',
                    'body' => 'same response to this question but for faq'
                ]
            ]
        ]);

        // to delete, covered by next assertion
        $this->assertRegExp('~^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|(\+|-)\d{2}(:?\d{2})?)$~', $response->toArray()['createdAt']);

        $this->assertMatchesResourceItemJsonSchema(Question::class);
    }

    public function testCreateInvalidQuestion(): void
    {
        $response = static::createClient()->request(
            'POST',
            '/questions',['json' => [

            ]
        ]);
        $this->assertResponseStatusCodeSame(400);

        $this->assertJsonContains([
            '@context'=> '/contexts/ConstraintViolationList',
            '@type'=> 'ConstraintViolationList',
            'hydra:title'=> 'An error occurred',
            'hydra:description'=> 'title: This value should not be blank.
promoted: This value should not be blank.
status: This value should not be blank.',
        ]);
    }

    public function testCreateInvalidAnswer(): void
    {
        $response = static::createClient()->request(
            'POST',
            '/questions',['json' => [
                'title' => 'question without a valid answer',
                'promoted' => true,
                'status' => 'draft',
                'answers' => [
                    []
                ]
        ]]);
        $this->assertResponseStatusCodeSame(400);

        $this->assertJsonContains([
            '@context'=> '/contexts/ConstraintViolationList',
            '@type'=> 'ConstraintViolationList',
            'hydra:title'=> 'An error occurred',
            'hydra:description'=> 'answers[0].channel: This value should not be blank.
answers[0].body: This value should not be blank.',
        ]);
    }

    public function testUpdateQuestion(): void
    {
        $client = static::createClient();

        $iri = $this->findIriBy(Question::class, ['id' => 3]);

        $client->request('PUT', $iri, ['json' => [
            'title' => 'updated title',
            'status' => 'draft',
            //'promoted' => true
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'title' => 'updated title',
            'status' => 'draft'
        ]);

        // check if transport is used
        /** @var InMemoryTransport $transport */
        $transport = static::$container->get('messenger.transport.async');
        $this->assertCount(1, $transport->get());
    }

    public function testDeleteQuestion(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Question::class, ['id' => 4]);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::$container->get('doctrine')->getRepository(Question::class)->findOneBy(['id' =>4])
        );
    }
}






