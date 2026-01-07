<?php

declare(strict_types=1);

namespace App\Tests\ConditionResolver;

use App\Condition\OlderThan;
use App\ConditionResolver\OlderThanResolver;
use App\Entity\Tree;
use App\Exception\InvalidContextException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OlderThanResolverTest extends KernelTestCase
{
    /**
     * @dataProvider provideItResolvesAge
     *
     * @param int  $age
     * @param bool $result
     *
     * @return void
     */
    public function testItResolvesAge(int $age, bool $result): void
    {
        $condition = new OlderThan(10);
        $resolver = self::getContainer()->get(OlderThanResolver::class);
        $tree = new Tree();
        $tree->setAge($age);

        self::assertSame($result, $resolver->resolve($condition, ['datable' => $tree]));
    }

    /**
     * min age is 10
     * 15 is true
     * 10 is false
     * 5 is false
     *
     * @return array
     */
    public function provideItResolvesAge(): array
    {
        return [
            [15, true],
            [10, false],
            [5, false],
        ];
    }

    /**
     * @return void
     */
    public function testItThrowsExceptionWithoutContext(): void
    {
        $condition = new OlderThan(10);
        $resolver = self::getContainer()->get(OlderThanResolver::class);
        self::expectException(InvalidContextException::class);
        $resolver->resolve($condition, []);
    }
}
