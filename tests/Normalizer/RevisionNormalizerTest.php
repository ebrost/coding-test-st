<?php

namespace App\Tests\Serializer\Normalizer;

use App\Entity\Revision;
use App\Serializer\Normalizer\RevisionNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class RevisionNormalizerTest extends WebTestCase
{

    private $normalizer;

    private $objectNormalizer;

    protected function setUp(): void
    {

        $this->objectNormalizer = $this->getMockBuilder(ObjectNormalizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->normalizer = new RevisionNormalizer($this->objectNormalizer);
    }


    public function testNormalize()
    {
        $revision = new Revision();
        $revision->setUpdatedContent([
            "title" =>["old Title.","new Title"],
            "promoted" =>[false,true]
        ]);

        $this->objectNormalizer
            ->expects($this->once())
            ->method('normalize')
            ->with($revision)
            ->willReturn(
                ['updatedContent'=>
                    [
                        "title" =>["old Title.","new Title"],
                        "promoted" =>[false,true]
                    ]
                ]
            );

        $data = $this->normalizer->normalize($revision);

        $this->assertEquals('title : \'old Title.\' => \'new Title\' *** promoted : false => true', $data['updatedContent'] );
    }

    public function testSupportNormalization()
    {
        $this->assertTrue($this->normalizer->supportsNormalization(new Revision()));
        $this->assertFalse($this->normalizer->supportsNormalization(new \stdClass()));
    }
}