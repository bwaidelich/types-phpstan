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
 * PHPStan rule that enforces final modifier for TypeBased classes.
 *
 * Classes with TypeBased attributes should be final to prevent inheritance
 * and ensure the type's integrity.
 *
 * @implements Rule<InClassNode>
 */
final readonly class TypedClassesMustBeFinalRule implements Rule
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

        if (!$classReflection->isFinal()) {
            $attributeName = $this->getTypeBasedAttributeName($classReflection);

            return [
                RuleErrorBuilder::message(sprintf(
                    'Class %s is marked with #[%s] and must be declared as final. Add the final modifier to the class declaration.',
                    $classReflection->getName(),
                    $this->getShortAttributeName($attributeName)
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
