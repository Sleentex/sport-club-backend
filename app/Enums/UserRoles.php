<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TRAINER()
 * @method static static CLIENT()
 */
final class UserRoles extends Enum
{
    const TRAINER = 'trainer';
    const CLIENT  = 'client';
}
