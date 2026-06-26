<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use Wwwision\Types\Schema\Dynamic\DynamicRecord;

/**
 * Teaches PHPStan that a {@see DynamicRecord} (a class-less dynamic schema instance, read via `__get`)
 * may be accessed with any property name, yielding a read-only `mixed`.
 *
 * Without this, object-accessor syntax (`$record->someProperty`) on a `DynamicRecord` would be
 * reported as access to an undefined property. Precise per-property types are not available because
 * a dynamic record's shape is only known at runtime.
 */
final class DynamicRecordPropertiesClassReflectionExtension implements PropertiesClassReflectionExtension
{
    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        return $classReflection->getName() === DynamicRecord::class;
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        return new DynamicRecordPropertyReflection($classReflection);
    }
}
