<?php

declare(strict_types=1);

namespace Brick\Math\Doctrine\Type;

use Brick\Math\BigDecimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;

class BigDecimalType extends Type
{
    public const NAME = 'bigdecimal';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDecimalTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value instanceof BigDecimal) {
            return $value;
        }

        try {
            return BigDecimal::of($value);
        } catch (\Exception $e) {
            throw ValueNotConvertible::new($value, BigDecimal::class);
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BigDecimal) {
            return (string) $value;
        }

        throw ValueNotConvertible::new($value, 'string');
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

}
