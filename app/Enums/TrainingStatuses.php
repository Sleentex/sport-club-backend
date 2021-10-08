<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACTIVE()
 * @method static static CANCELLED()
 */
final class TrainingStatuses extends Enum
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const IN_PROGRESS = 'in_progress';
    const FINISHED = 'finished';
    const CANCELLED  = 'cancelled';

}
