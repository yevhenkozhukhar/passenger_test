<?php

namespace App\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QueryRequestResolver
{
    public function __construct(
//        private readonly SerializerInterface&DenormalizerInterface $serializer,
//        private readonly ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$data = $request->query->all()) {
            return null;
        }


//        return $this->serializer->denormalize($data, $type, null, $attribute->serializationContext + self::CONTEXT_DENORMALIZE);
//        $attribute = $argument
//            ->getAttributesOfType(MapQueryString::class, ArgumentMetadata::IS_INSTANCEOF)[0] ?? null;
//
//        if (!$attribute) {
//            return [];
//        }
//
//        if ($argument->isVariadic()) {
//            throw new \LogicException(sprintf('Mapping variadic argument "$%s" is not supported.', $argument->getName()));
//        }
//
//        $attribute->metadata = $argument;
////        dump($attribute);
////        dump($argument);
////        die;
//
//        return [$attribute];
    }
}