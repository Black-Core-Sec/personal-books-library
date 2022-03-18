<?php
declare(strict_types=1);

namespace Api\Controller;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController
{
    /**
     * XML|JSON Serializer
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;

    public function __construct(SerializerInterface $serializerBuilder)
    {
        $this->serializer = $serializerBuilder;
    }

    public function response($data)
    {
        return new JsonResponse($data);
    }
}
