<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Wwwision\Types\Attributes\TypeBased;

/**
 * PHPStan rule that enforces private constructors for TypeBased classes.
 *
 * Classes with TypeBased attributes should have private constructors to prevent
 * direct instantiation and enforce the use of factory methods.
 *
 * @implements Rule<InClassNode>
 */
final readonly class TypedClassesMustHavePrivateConstructorRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     * @return list<\PHPStan\Rules\RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $node->getClassReflection();

        if (!$this->hasTypeBasedAttribute($classReflection)) {
            return [];
        }

        $nativeReflection = $classReflection->getNativeReflection();

        if (!$nativeReflection->hasMethod('__construct')) {
            // No explicit constructor - this is fine, PHP will use default constructor
            return [];
        }

        $constructor = $nativeReflection->getMethod('__construct');

        if (!$constructor->isPrivate()) {
            $visibility = $constructor->isPublic() ? 'public' : 'protected';
            $attributeName = $this->getTypeBasedAttributeName($classReflection);

            return [
                RuleErrorBuilder::message(sprintf(
                    'Class %s is marked with #[%s] and must have a private constructor, but it has a %s constructor. Change the constructor visibility to private.',
                    $classReflection->getName(),
                    $this->getShortAttributeName($attributeName),
                    $visibility
                ))->build(),
            ];
        }

        return [];
    }

    private function hasTypeBasedAttribute(ClassReflection $classReflection): bool
    {
        foreach ($classReflection->getNativeReflection()->getAttributes() as $attribute) {
            $attributeClass = $attribute->getName();

            try {
                $attributeInstance = $attribute->newInstance();
                if ($attributeInstance instanceof TypeBased) {
                    return true;
                }
            } catch (\Throwable) {
                if (is_subclass_of($attributeClass, TypeBased::class)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getTypeBasedAttributeName(ClassReflection $classReflection): string
    {
        foreach ($classReflection->getNativeReflection()->getAttributes() as $attribute) {
            $attributeClass = $attribute->getName();

            try {
                $attributeInstance = $attribute->newInstance();
                if ($attributeInstance instanceof TypeBased) {
                    return $attributeClass;
                }
            } catch (\Throwable) {
                if (is_subclass_of($attributeClass, TypeBased::class)) {
                    return $attributeClass;
                }
            }
        }

        return '';
    }

    private function getShortAttributeName(string $fullyQualifiedAttributeName): string
    {
        $parts = explode('\\', $fullyQualifiedAttributeName);
        return end($parts);
    }
}
