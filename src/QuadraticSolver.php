<?php

namespace App;

use App\Interfaces\SolverInterface;
use InvalidArgumentException;

class QuadraticSolver implements SolverInterface
{
    /**
     * Погрешность сравнения для float.
     * Используется вместо сравнения с нулем
     */
    private const float EPSILON = 1e-10;

    /**
     * Решает квадратное уравнение по коэффициентам a, b, c
     *
     * @param float $a
     * @param float $b
     * @param float $c
     * @return float[] массив корней (может быть 0, 1 или 2 значений)
     *
     * @throws InvalidArgumentException если входные данные некорректны
     */
    public function solve(float $a, float $b, float $c): array
    {
        // Проверка, что все коэффициенты — конечные числа
        if ($this->hasInvalidValues($a, $b, $c)) {
            throw new InvalidArgumentException("Коэффициенты должны быть конечными числами.");
        }

        // Проверка на ноль
        if ($this->isZero($a)) {
            throw new InvalidArgumentException("Коэффициент 'a' не может быть равен 0.");
        }

        // Выполнение основного алгоритма решения уравнения
        return $this->quadraticSolve($a, $b, $c);
    }

    /**
     * Проверяет, что все переданные значения — допустимые числа
     *
     * @param float ...$values
     * @return bool
     */
    private function hasInvalidValues(float ...$values): bool
    {
        foreach ($values as $v) {
            if (is_nan($v) || is_infinite($v)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет, близко ли значение к нулю с учетом погрешности
     *
     * @param float $value
     * @return bool
     */
    private function isZero(float $value): bool
    {
        return abs($value) < self::EPSILON;
    }

    /**
     * Основная логика решения квадратного уравнения через дискриминант
     *
     * @param float $a
     * @param float $b
     * @param float $c
     * @return float[] массив корней
     */
    private function quadraticSolve(float $a, float $b, float $c): array
    {
        // Вычисление дискриминанта
        $d = $b ** 2 - 4 * $a * $c;

        // Корней нет
        if ($d < -self::EPSILON) {
            return [];
        }

        // Один корень
        if ($this->isZero($d)) {
            $x = -$b / (2 * $a);
            return [$x];
        }

        // Два корня
        $sqrtD = sqrt($d);
        $x1 = (-$b + $sqrtD) / (2 * $a);
        $x2 = (-$b - $sqrtD) / (2 * $a);

        return [$x1, $x2];
    }
}
