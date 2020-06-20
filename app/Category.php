<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Category extends Model
{
    //
    /*protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';*/

    protected $fillable = [
        'name', 'parent_id', 'status',
    ];

    public $timestamps = true;

    protected $perPage = 10;

    public static function getValidator($data, $except = 0)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'unique:categories,name,' . $except,
            ],
            'parent_id' => [
                'nullable',
                'int',
                'exists:categories,id'
            ],
            'status' => [
                'required',
                'string',
                'in:published,draft'
            ],
        ];
        $validator = Validator::make($data, $rules, [
            'required' => ':attribute Required data!!'
        ]);
        return $validator;
    }
}
