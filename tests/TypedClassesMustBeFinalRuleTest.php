<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Wwwision\TypesPhpStan\TypedClassesMustBeFinalRule;

/**
 * @extends RuleTestCase<TypedClassesMustBeFinalRule>
 */
final class TypedClassesMustBeFinalRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TypedClassesMustBeFinalRule();
    }

    public function testFinalClassWithStringBasedAttributePasses(): void
    {
        $this->analyse([__DIR__ . '/data/FinalClassWithStringBased.php'], []);
    }

    public function testNonFinalClassWithStringBasedAttributeFails(): void
    {
        $this->analyse([__DIR__ . '/data/NonFinalClassWithStringBased.php'], [
            [
                'Class Wwwision\TypesPhpStan\Tests\Data\NonFinalClassWithStringBased is marked with #[StringBased] and must be declared as final. Add the final modifier to the class declaration.',
                9,
            ],
        ]);
    }

    public function testNonFinalClassWithIntegerBasedAttributeFails(): void
    {
        $this->analyse([__DIR__ . '/data/NonFinalClassWithIntegerBased.php'], [
            [
                'Class Wwwision\TypesPhpStan\Tests\Data\NonFinalClassWithIntegerBased is marked with #[IntegerBased] and must be declared as final. Add the final modifier to the class declaration.',
                9,
            ],
        ]);
    }

    public function testClassWithoutAttributePasses(): void
    {
        $this->analyse([__DIR__ . '/data/ClassWithoutAttribute.php'], []);
    }
}
