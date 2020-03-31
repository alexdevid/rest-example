<?php

namespace App\Serializer;

use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;

/**
 * @author Alexander Tsukanov <https://alexdevid.com>
 */
class ObjectConstructor implements ObjectConstructorInterface
{
    /**
     * @param DeserializationVisitorInterface $visitor
     * @param ClassMetadata $metadata
     * @param mixed $data
     * @param array $type
     * @param DeserializationContext $context
     * @return object|null
     */
    public function construct(DeserializationVisitorInterface $visitor, ClassMetadata $metadata, $data, array $type, DeserializationContext $context): ?object
    {
        $className = $metadata->name;

        return new $className();
    }
}