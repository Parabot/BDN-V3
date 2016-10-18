<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use AppBundle\Service\Normalizers\DateTimeNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerManager {

    /**
     * @param        $object
     * @param string $format
     * @param array  $groups
     *
     * @return object
     */
    public static function normalize($object, $format = 'json', $groups = [ 'default' ]) {
        return SerializerManager::getSerializers()->normalize($object, $format, [ 'groups' => $groups ]);
    }

    /**
     * @return Serializer
     */
    public static function getSerializers() {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $encoders    = [ new XmlEncoder(), new JsonEncoder() ];
        $normalizers = [ new DateTimeNormalizer(), new ObjectNormalizer($classMetadataFactory) ];

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer;
    }
}