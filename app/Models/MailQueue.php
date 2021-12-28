<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $mail_to
 * @property string $mail_class
 * @property array $mail_params
 * @property integer $priority
 * @property integer $executed
 * @property string $executed_at
 */
class MailQueue extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'mail_params' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'mail_to',
        'mail_class',
        'mail_params',
        'priority',
        'executed',
        'executed_at'
    ];
}
