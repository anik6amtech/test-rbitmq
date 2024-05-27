<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Class BaseModel
 *
 * Represents a user in the application.
 *
 * @package App\Models
 * @mixin Builder
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class BaseModel extends Model
{
    use HasFactory;
}
