<?php

declare(strict_types=1);

namespace Brick\Math\Doctrine\Type;

use Brick\Math\BigInteger;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;

class BigIntegerType extends Type
{
    public const NAME = 'biginteger';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBigIntTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value instanceof BigInteger) {
            return $value;
        }

        try {
            return BigInteger::of($value);
        } catch (\Exception $e) {
            throw ValueNotConvertible::new($value, BigInteger::class);
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BigInteger) {
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
