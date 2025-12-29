<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['student_id', 'module_id', 'score', 'absences'];
    
  
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
