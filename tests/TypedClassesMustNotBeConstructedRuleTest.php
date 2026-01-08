<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Wwwision\TypesPhpStan\TypedClassesMustNotBeConstructedRule;

/**
 * @extends RuleTestCase<TypedClassesMustNotBeConstructedRule>
 */
final class TypedClassesMustNotBeConstructedRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TypedClassesMustNotBeConstructedRule(
            self::createReflectionProvider()
        );
    }

    public function testDirectInstantiationOfStringBasedClassFails(): void
    {
        $this->analyse([__DIR__ . '/data/DirectInstantiationOfStringBased.php'], [
            [
                'Instantiation of class Wwwision\TypesPhpStan\Tests\Data\FinalClassWithStringBased is forbidden because it is marked with #[StringBased]. Use `Wwwision\Types\instantiate(Wwwision\TypesPhpStan\Tests\Data\FinalClassWithStringBased::class, $value)` instead.',
                9,
            ],
        ]);
    }

    public function testDirectInstantiationOfIntegerBasedClassFails(): void
    {
        $this->analyse([__DIR__ . '/data/DirectInstantiationOfIntegerBased.php'], [
            [
                'Instantiation of class Wwwision\TypesPhpStan\Tests\Data\IntegerBasedClass is forbidden because it is marked with #[IntegerBased]. Use `Wwwision\Types\instantiate(Wwwision\TypesPhpStan\Tests\Data\IntegerBasedClass::class, $value)` instead.',
                9,
            ],
        ]);
    }

    public function testDynamicInstantiationWithClassStringFails(): void
    {
        $this->analyse([__DIR__ . '/data/DynamicInstantiationWithClassString.php'], [
            [
                'Instantiation of class Wwwision\TypesPhpStan\Tests\Data\FinalClassWithStringBased is forbidden because it is marked with #[StringBased]. Use `Wwwision\Types\instantiate(Wwwision\TypesPhpStan\Tests\Data\FinalClassWithStringBased::class, $value)` instead.',
                10,
            ],
        ]);
    }

    public function testInstantiationOfClassWithoutAttributePasses(): void
    {
        $this->analyse([__DIR__ . '/data/InstantiationOfClassWithoutAttribute.php'], []);
    }
}
