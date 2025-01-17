<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;

/**
 * Normalizes a {@see \BackedEnum} enumeration to a string or an integer.
 *
 * @author Alexandre Daubois <alex.daubois@gmail.com>
 */
final class BackedEnumNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     *
     * @return int|string
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!$object instanceof \BackedEnum) {
            throw new InvalidArgumentException('The data must belong to a backed enumeration.');
        }

        return $object->value;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \BackedEnum;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (!is_subclass_of($type, \BackedEnum::class)) {
            throw new InvalidArgumentException('The data must belong to a backed enumeration.');
        }

        if (!\is_int($data) && !\is_string($data)) {
            throw new NotNormalizableValueException('The data is neither an integer nor a string, you should pass an integer or a string that can be parsed as an enumeration case of type '.$type.'.');
        }

        try {
            return $type::from($data);
        } catch (\ValueError $e) {
            throw new NotNormalizableValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_subclass_of($type, \BackedEnum::class);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
