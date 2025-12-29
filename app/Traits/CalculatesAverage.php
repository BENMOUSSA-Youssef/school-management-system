<?php

namespace App\Traits;

trait CalculatesAverage
{
    /**
     * Calculate the weighted average grade for a student
     * @param \Illuminate\Database\Eloquent\Collection $grades
     * @return float|null
     */
    public static function calculateAverage($grades)
    {
        if ($grades->isEmpty()) {
            return null;
        }
        
        $totalPoints = 0;
        $totalCoefficient = 0;
        
        foreach ($grades as $grade) {
            if ($grade->score !== null) {
                $module = $grade->module;
                $coefficient = $module ? $module->coefficient : 1;
                $totalPoints += $grade->score * $coefficient;
                $totalCoefficient += $coefficient;
            }
        }
        
        if ($totalCoefficient === 0) {
            return null;
        }
        
        return $totalPoints / $totalCoefficient;
    }
    
    /**
     * Get mention based on average
     * @param float|null $average
     * @return string
     */
    public static function getMention($average)
    {
        if ($average === null) {
            return 'N/A';
        }
        
        if ($average >= 16) return 'Très Bien';
        if ($average >= 14) return 'Bien';
        if ($average >= 12) return 'Assez Bien';
        if ($average >= 10) return 'Passable';
        return 'Ajourné';
    }
    
    /**
     * Determine status based on average
     * @param float|null $average
     * @return string
     */
    public static function determineStatus($average)
    {
        if ($average === null) {
            return 'N/A';
        }
        
        return $average >= 10 ? 'Pass' : 'Fail';
    }
}

