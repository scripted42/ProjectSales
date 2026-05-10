<?php

namespace {
    if (! class_exists('NumberFormatter')) {
        class NumberFormatter
        {
            public const DECIMAL = 1;
            public const CURRENCY = 2;
            public const PERCENT = 3;
            public const SCIENTIFIC = 4;
            public const SPELLOUT = 5;
            public const ORDINAL = 6;
            public const DURATION = 7;
            public const PATTERN_RULEBASED = 8;
            public const IGNORE = 0;
            public const DEFAULT_STYLE = 1;
            public const MAX_FRACTION_DIGITS = 0;
            public const FRACTION_DIGITS = 0;

            public function __construct(string $locale = 'en', int $style = 1, ?string $pattern = null)
            {
            }

            public function format(int|float $value, int $type = 0): string|false
            {
                return (string) $value;
            }

            public function setAttribute(int $attribute, int|float $value): bool
            {
                return true;
            }

            public function getAttribute(int $attribute): int|float|false
            {
                return 0;
            }

            public function setTextAttribute(int $attribute, string $value): bool
            {
                return true;
            }

            public function setSymbol(int $symbol, string $value): bool
            {
                return true;
            }

            public function formatCurrency(int|float $value, string $currency): string|false
            {
                return $currency . ' ' . (string)$value;
            }
        }
    }
}

namespace App\Providers {
    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            //
        }

        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            //
        }
    }
}
