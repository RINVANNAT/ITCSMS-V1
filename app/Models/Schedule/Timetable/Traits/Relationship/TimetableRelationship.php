<?php

namespace App\Models\Schedule\Timetable\Traits\Relationship;

use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use App\Models\Semester;

/**
 * Class TimetableRelationship
 * @package App\Models\Schedule\Timetable\Traits\Relationship
 */
trait TimetableRelationship
{
    /**
     * Get associated model with AcademicYear.
     *
     * @return mixed
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get associated model with Department.
     *
     * @return mixed
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get associated model with Degree.
     *
     * @return mixed
     */
    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

    /**
     * Get associated model with Grade.
     *
     * @return mixed
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get associated model with DepartmentOption.
     *
     * @return mixed
     */
    public function option()
    {
        return $this->belongsTo(DepartmentOption::class);
    }

    /**
     * Get associated with model Semester.
     *
     * @return mixed
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get associated with model Group.
     *
     * @return mixed
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get associated with model Week.
     *
     * @return mixed
     */
    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    /**
     * Get associated with model TimetableSlot.
     *
     * @return mixed
     */
    public function timetableSlots()
    {
        return $this->hasMany(TimetableSlot::class);
    }
}