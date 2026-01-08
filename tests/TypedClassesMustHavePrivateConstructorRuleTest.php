<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Wwwision\TypesPhpStan\TypedClassesMustHavePrivateConstructorRule;

/**
 * @extends RuleTestCase<TypedClassesMustHavePrivateConstructorRule>
 */
final class TypedClassesMustHavePrivateConstructorRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TypedClassesMustHavePrivateConstructorRule();
    }

    public function testClassWithPrivateConstructorPasses(): void
    {
        $this->analyse([__DIR__ . '/data/ClassWithPrivateConstructor.php'], []);
    }

    public function testClassWithPublicConstructorFails(): void
    {
        $this->analyse([__DIR__ . '/data/ClassWithPublicConstructor.php'], [
            [
                'Class Wwwision\TypesPhpStan\Tests\Data\ClassWithPublicConstructor is marked with #[StringBased] and must have a private constructor, but it has a public constructor. Change the constructor visibility to private.',
                9,
            ],
        ]);
    }

    public function testClassWithProtectedConstructorFails(): void
    {
        $this->analyse([__DIR__ . '/data/ClassWithProtectedConstructor.php'], [
            [
                'Class Wwwision\TypesPhpStan\Tests\Data\ClassWithProtectedConstructor is marked with #[IntegerBased] and must have a private constructor, but it has a protected constructor. Change the constructor visibility to private.',
                9,
            ],
        ]);
    }

    public function testClassWithoutExplicitConstructorPasses(): void
    {
        $this->analyse([__DIR__ . '/data/ClassWithoutExplicitConstructor.php'], []);
    }

    public function testClassWithoutAttributePasses(): void
    {
        $this->analyse([__DIR__ . '/data/ClassWithoutAttribute.php'], []);
    }
}
