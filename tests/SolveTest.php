<?php

namespace Tests;

use App\QuadraticSolver;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SolveTest extends TestCase
{
    private QuadraticSolver $solver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solver = new QuadraticSolver();
    }
    
    /**
     * Проверяет, что для уравнения x^2+1 = 0 корней нет
     * 
     * @return void
     */
    public function testItReturnsNoRealRootsWhenDiscriminantIsNegative(): void
    {
        $result = $this->solver->solve(1, 0, 1);
        $this->assertSame([], $result);
    }

    /**
     * Проверяет, что для уравнения x^2-1 = 0 есть два корня кратности 1
     * 
     * @return void
     */
    public function testItReturnsTwoRealRootsWhenDiscriminantIsPositive(): void
    {
        $result = $this->solver->solve(1, 0, -1);
        sort($result);
        
        $this->assertCount(2, $result);
        $this->assertEquals(-1, $result[0]);
        $this->assertEquals(1, $result[1]);
    }

    /**
     * Проверяет, что для уравнения есть один корень кратности 2
     * дискриминант ровно равен 0
     * @return void
     */
    public function testItReturnsOneRootWhenDiscriminantIsZero(): void
    {
        $result = $this->solver->solve(1, 2, 1);
        
        $this->assertCount(1, $result);
        $this->assertEquals(-1, $result[0]);
    }

    /**
     * Проверяет, что для уравнения есть один корень кратности 2
     * дискриминант не ноль, но меньше заданного epsilon
     * 
     * @return void
     */
    public function testItReturnsOneRootWhenDiscriminantIsLessThanEpsilon(): void
    {
        $a = 1.0;
        $b = 2.0;
        $c = 1.0 + 1e-12;

        $result = $this->solver->solve($a, $b, $c);
        $this->assertCount(1, $result); 
        $this->assertEqualsWithDelta(-1.0, $result[0], 1e-6);
    }

    /**
     * Проверяет, что коэффициент 'a' не может быть равен 0, с учетом, что 'a' - число с плавающей точкой
     * 
     * @return void
     */
    public function testItThrowsWhenAIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->solver->solve(0.0, 2.0, 1.0);
    }

    /**
     * Проверяет, что solve не может принимать значения, отличные от чиcел
     * 
     * @return void
     */
    public function testItThrowsWhenCoefficientsAreNanOrInfinite(): void
    {
        $invalidValues = [
            [NAN, 1, 1],
            [1, INF, 1],
            [1, 1, -INF],
        ];

        foreach ($invalidValues as [$a, $b, $c]) {
            try {
                $this->solver->solve($a, $b, $c);
                $this->fail("Expected InvalidArgumentException not thrown for a={$a}, b={$b}, c={$c}");
            } catch (InvalidArgumentException $e) {
                $this->assertStringContainsString('Коэффициенты должны быть конечными числами', $e->getMessage());
            }
        }
    }
}
