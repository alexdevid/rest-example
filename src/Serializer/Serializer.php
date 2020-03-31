<?php

namespace App\Serializer;

use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Alexander Tsukanov <https://alexdevid.com>
 */
class Serializer
{
    public const FORMAT_JSON = 'json';
    public const FORMAT_XML = 'xml';
    public const FORMAT_DEFAULT = self::FORMAT_JSON;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->serializer = SerializerBuilder::create()
            ->setObjectConstructor(new ObjectConstructor())
            ->build();

        $this->requestStack = $requestStack;
    }

    /**
     * @param mixed $serializable
     * @return string
     */
    public function serialize($serializable): string
    {
        return $this->serializer->serialize($serializable, $this->getResponseType());
    }

    /**
     * @param string $content
     * @param string $type
     * @return object|null
     */
    public function deserialize(string $content, string $type): ?object
    {
        return $this->serializer->deserialize($content, $type, $this->getResponseType());
    }

    /**
     * @return string
     */
    private function getResponseType(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $requestType = $request->getContentType();

        if (in_array($requestType, [self::FORMAT_XML, self::FORMAT_JSON])) {
            return $requestType;
        }

        return self::FORMAT_DEFAULT;
    }
}