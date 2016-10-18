<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service\Normalizers;

use InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;
use UnexpectedValueException;

class DateTimeNormalizer implements NormalizerInterface, DenormalizerInterface {
    const FORMAT_KEY = 'datetime_format';

    /**
     * @var string
     */
    private $format;

    /**
     * @param string $format
     */
    public function __construct($format = \DateTime::RFC3339) {
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function normalize($object, $format = null, array $context = []) {
        if( ! $object instanceof \DateTimeInterface) {
            throw new InvalidArgumentException('The object must implement the "\DateTimeInterface".');
        }

        $format = isset($context[ self::FORMAT_KEY ]) ? $context[ self::FORMAT_KEY ] : $this->format;

        return $object->format($format);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null) {
        return $data instanceof \DateTimeInterface;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnexpectedValueException
     */
    public function denormalize($data, $class, $format = null, array $context = []) {
        try {
            return \DateTime::class === $class ? new \DateTime($data) : new \DateTimeImmutable($data);
        } catch(\Exception $e) {
            throw new UnexpectedValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null) {
        $supportedTypes = [
            \DateTimeInterface::class => true,
            \DateTimeImmutable::class => true,
            \DateTime::class          => true,
        ];

        return isset($supportedTypes[ $type ]);
    }
}