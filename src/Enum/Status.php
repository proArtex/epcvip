<?php declare(strict_types=1);

namespace App\Enum;

class Status
{
    public const NEW = 'new';
    public const PENDING = 'pending';
    public const IN_REVIEW = 'in review';
    public const APPROVED = 'approved';
    public const INACTIVE = 'inactive';
    public const DELETED = 'deleted';

    /**
     * @return string[]
     */
    public static function allExternal(): array
    {
        return [
            self::PENDING,
            self::IN_REVIEW,
            self::APPROVED,
            self::INACTIVE
        ];
    }
}
