<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CalculatesAverage;

class Student extends Model
{
    protected $fillable = [
        'full_name', 
        'registration_number', 
        'class',
        'user_id'
    ];
    
   
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    
   
    
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    
    
     
    public function getAverageGradeAttribute()
    {// daruri because ila grade makanosh ! laravel would run a sql query o hadshi aydir lina some problems ! 
        if (!$this->relationLoaded('grades')) {
            $this->load('grades'); // load l graddes from database ! 
        }
                // hadi wahd trait (helper logic)
        $average = CalculatesAverage::calculateAverage($this->grades);
        return $average !== null ? round($average, 2) : null; 

        // Send all the student’s grades to a helper function that calculates the average.
    }
    
    
      //Get the status based on average
     
    public function getStatusAttribute()
    {
        return CalculatesAverage::determineStatus($this->average_grade);
    }
}