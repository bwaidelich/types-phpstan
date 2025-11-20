<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\Type;
use Wwwision\Types\Attributes\TypeBased;

/**
 * PHPStan rule that prevents direct instantiation of classes marked with TypeBased attributes.
 *
 * Classes with TypeBased attributes (StringBased, IntegerBased, FloatBased, ListBased) should
 * only be instantiated through their designated factory methods, not via direct construction.
 */
final readonly class TypedClassesMustNotBeConstructedRule implements Rule
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getNodeType(): string
    {
        return New_::class;
    }

    /**
     * @param New_ $node
     * @return list<\PHPStan\Rules\RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classNamesToCheck = $this->extractClassNames($node, $scope);

        foreach ($classNamesToCheck as $className) {
            if (!$this->reflectionProvider->hasClass($className)) {
                continue;
            }

            $classReflection = $this->reflectionProvider->getClass($className);
            $forbiddenAttribute = $this->findForbiddenAttribute($classReflection);

            if ($forbiddenAttribute !== null) {
                return [
                    RuleErrorBuilder::message(sprintf(
                        'Instantiation of class %1$s is forbidden because it is marked with #[%2$s]. Use `Wwwision\Types\instantiate(%1$s::class, $value)` instead.',
                        $className,
                        substr($forbiddenAttribute, strrpos($forbiddenAttribute, '\\') + 1)
                    ))->build(),
                ];
            }
        }

        return [];
    }

    /**
     * Extract all possible class names from a New_ expression.
     *
     * @return list<string>
     */
    private function extractClassNames(New_ $node, Scope $scope): array
    {
        // Static class names: new Foo(), new \Fully\Qualified\Bar()
        if ($node->class instanceof Node\Name) {
            return [$scope->resolveName($node->class)];
        }

        // Dynamic class names: new $className(), new ($var)(), new (self::class)()
        return $this->extractDynamicClassNames($scope->getType($node->class));
    }

    /**
     * Extract class names from dynamic type expressions.
     *
     * @return list<string>
     */
    private function extractDynamicClassNames(Type $type): array
    {
        $classNames = [];

        // Handle class-string literal types (e.g., self::class stored in a variable)
        if ($type instanceof ConstantStringType) {
            $className = $type->getValue();
            if ($this->reflectionProvider->hasClass($className)) {
                $classNames[] = $className;
            }
        }

        // Handle object types from variables or expressions
        foreach ($type->getObjectClassNames() as $className) {
            $classNames[] = $className;
        }

        return $classNames;
    }

    /**
     * Check if a class has any TypeBased attribute and return the attribute name if found.
     */
    private function findForbiddenAttribute(ClassReflection $classReflection): ?string
    {
        $nativeReflection = $classReflection->getNativeReflection();

        foreach ($nativeReflection->getAttributes() as $attribute) {
            $attributeClass = $attribute->getName();

            // Try to instantiate the attribute to ensure the class is loaded
            try {
                $attributeInstance = $attribute->newInstance();

                // Check if the attribute implements TypeBased interface
                if ($attributeInstance instanceof TypeBased) {
                    return $attributeClass;
                }
            } catch (\Throwable $e) {
                // If attribute cannot be instantiated, fall back to checking the class
                if (is_subclass_of($attributeClass, TypeBased::class)) {
                    return $attributeClass;
                }
            }
        }

        return null;
    }
}