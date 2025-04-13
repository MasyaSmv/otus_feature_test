<?php

namespace App\Interfaces;

use InvalidArgumentException;

interface SolverInterface
{
    /**
     * Решает квадратное уравнение: ax^2 + bx + c = 0
     *
     * @param float $a
     * @param float $b
     * @param float $c
     * @return float[] массив действительных корней
     * @throws InvalidArgumentException если параметры некорректны
     */
    public function solve(float $a, float $b, float $c): array;
}