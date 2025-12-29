<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'code', 'coefficient', 'exam_date'];
    
    protected $casts = [ // kitkhad mn base de donnee o kitpassa l object date ! o t9dr tl3b bih kif ma bghiti !  
        'exam_date' => 'date',
    ];
    
    /**
     * Get all grades for this module
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
