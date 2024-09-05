<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\ValueObject\Concerns;

use LengthException;

trait SanitizesNumbers
{
    protected function sanitize(int|string|float|null $number): string
    {
        if (is_float($number) && ! $this->isPrecise($number)) {
            throw new LengthException('Float precision loss detected.');
        }

        $number = str($number)->replace(',', '.');

        $dots = $number->substrCount('.');

        if ($dots >= 2) {
            $number = $number
                ->replaceLast('.', ',')
                ->replace('.', '')
                ->replaceLast(',', '.');
        }

        return $number
            ->replaceMatches('/[^0-9.]/', '')
            ->toString();
    }

    /**
     * Determine whether the passed floating point number is precise.
     */
    protected function isPrecise(float $number): bool
    {
        $numberAsString = str($number);

        $afterFloatingPoint = $numberAsString
            ->explode('.')
            ->get(1, '');

        $precisionPosition = str($afterFloatingPoint)->length();

        $roundedNumber = round($number, $precisionPosition);

        return $roundedNumber === $number && $numberAsString->length() <= PHP_FLOAT_DIG;
    }
}
