<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ScheduleComponent extends Component
{

    /**
     * Morning schedule
     */
    public $morningSchedule = [
        [
            '8:00', // Start hour
            '8:55', // End hour
            '#CCFFFF', // Item color
            'commun', // Item type
            '#fff' // background color
        ],
        ['8:55', '9:50', '#CCFFCC', 'commun', '#fff'],
        ['9:50', '10:45', '#CCCCFF', 'commun', '#fff'],
        ['10:45', '11:15', '#fff', 'break', '#ddd'],
        ['11:15', '12:10', '#FFCCFF', 'commun', '#fff'],
        ['12:10', '13:05', '#FFFF99', 'commun', '#fff'],
        ['13:05', '14:00', '#dfdfdF', 'commun', '#fff'],
    ];

    /**
     * schedule days
     */
    public $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    /**
     * All absences of the school
     */
    public $absences = [];


    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.schedule-component');
    }


    /**
     * Get all absences
     */	
    function getAllAbsences() {
        $absences = DB::table('absences')
                ->orderBy('hourNumber', 'asc')
                ->get();

        $this->absences = $absences;
    }


    /**
     * Mount the component
     */
    function mount(){
        $this->getAllAbsences();
    }


    
}
