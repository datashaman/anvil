<?php

namespace Datashaman\Anvil;

use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anvil_runs';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'started_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'input' => 'json',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Prevent Eloquent from overriding uuid with `lastInsertId`.
     *
     * @var bool
     */
    public $incrementing = false;
}
