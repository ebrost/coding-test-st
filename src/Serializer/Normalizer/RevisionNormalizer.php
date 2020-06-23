<?php

namespace App\Serializer\Normalizer;

use App\Entity\Revision;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class RevisionNormalizer
 * @package App\Serializer\Normalizer
 */
class RevisionNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param Revision $object
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $updatedContentParts = [];

        // format updatedContent to be more readable
        foreach ($data['updatedContent'] as $updatedPartKey => $updatedPartValue) {
            /*
             * $updatedPartValue[0] is previous content
             * $updatedPartValue[1] is new content
             * uses var_export for boolean values
             */
            $updatedContentParts[] = $updatedPartKey.' : '.var_export($updatedPartValue[0], true).' => '.var_export($updatedPartValue[1], true);
        }
        $data['updatedContent'] = implode(' *** ', $updatedContentParts);

        //remove entityClass name as this is not useful
        unset($data['entityClass']);

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Revision;
    }
}
