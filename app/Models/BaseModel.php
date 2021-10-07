<?php


namespace App\Models;

/**
 * @OA\Schema(
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 * )
 * Class BaseModel
 *
 * @package App\Models
 */
/*Abstract class to describe common fields in most models, used in swagger docs*/
abstract class BaseModel extends Model
{

}



