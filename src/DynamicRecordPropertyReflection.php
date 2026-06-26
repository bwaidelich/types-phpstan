<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;

/**
 * Reflection for a (magic) property of a {@see \Wwwision\Types\Schema\Dynamic\DynamicRecord}.
 *
 * A dynamic record is read via `__get` and is immutable, so every property is reported as a
 * readable (but not writable) `mixed` value.
 */
final class DynamicRecordPropertyReflection implements PropertyReflection
{
    public function __construct(
        private readonly ClassReflection $declaringClass,
    ) {}

    public function getReadableType(): Type
    {
        return new MixedType();
    }

    public function getWritableType(): Type
    {
        return new MixedType();
    }

    public function canChangeTypeAfterAssignment(): bool
    {
        return false;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getDocComment(): ?string
    {
        return null;
    }
}
