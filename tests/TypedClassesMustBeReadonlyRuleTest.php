<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Wwwision\TypesPhpStan\TypedClassesMustBeReadonlyRule;

/**
 * @extends RuleTestCase<TypedClassesMustBeReadonlyRule>
 */
final class TypedClassesMustBeReadonlyRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TypedClassesMustBeReadonlyRule();
    }

    public function testReadonlyClassWithStringBasedAttributePasses(): void
    {
        $this->analyse([__DIR__ . '/data/ReadonlyClassWithStringBased.php'], []);
    }

    public function testNonReadonlyClassWithStringBasedAttributeFails(): void
    {
        $this->analyse([__DIR__ . '/data/NonReadonlyClassWithStringBased.php'], [
            [
                'Class Wwwision\TypesPhpStan\Tests\Data\NonReadonlyClassWithStringBased is marked with #[StringBased] and must be declared as readonly. Add the readonly modifier to the class declaration.',
                9,
            ],
        ]);
    }

    public function testNonReadonlyClassWithFloatBasedAttributeFails(): void
    {
        $this->analyse([__DIR__ . '/data/NonReadonlyClassWithFloatBased.php'], [
            [
                'Class Wwwision\TypesPhpStan\Tests\Data\NonReadonlyClassWithFloatBased is marked with #[FloatBased] and must be declared as readonly. Add the readonly modifier to the class declaration.',
                9,
            ],
        ]);
    }

    public function testClassWithoutAttributePasses(): void
    {
        $this->analyse([__DIR__ . '/data/ClassWithoutAttribute.php'], []);
    }
}
