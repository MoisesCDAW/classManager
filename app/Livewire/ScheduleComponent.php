<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ScheduleComponent extends Component
{

    /**
     * Morning schedule, The hour numbers will be saved, with a start and end pair considered a single block.
     */
    public $morningSchedule = [
        [
            '8:00', // Start hour
            '8:55', // End hour
            '#CCFFFF', // Item color
            '#FFF' // background color
        ],
        ['8:55', '9:50', '#CCFFCC', '#FFF'],
        ['9:50', '10:45', '#CCCCFF', '#FFF'],
        ['10:45', '11:15', '#FFFFFF', '#DDD'],
        ['11:15', '12:10', '#FFCCFF', '#FFF'],
        ['12:10', '13:05', '#FFFF99', '#FFF'],
        ['13:05', '14:00', '#DFDFDF', '#FFF'],
    ];

    /**
     * schedule days, The days start at 0 with Monday being the first day.
     */
    public $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    /**
     * All absences of the school
     */
    public $absences = [];

    /**
     * Absences total per day, It will be calculated dynamically based on the day the loop pointer is at in the view of this component.
     */
    public $absencesTotalPerDay = 0;

    /**
     * True to show the add absence form.
     */
    public $viewAddAbsence = false;

    /**
     * True to show all absences when clicking on a specific day.
     */
    public $viewAllAbsences = false;

    /**
     * True to show the edit absence form.
     */
    public $viewEditAbsence = false;


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
     * First, reset the absence counter, then run a loop to go through the "absences" table and check 
     * which records match the day number and time block number passed as parameters. 
     * If there is a match, add +1 to the counter.
     * 
     * return true if there is any absence in the day and time block
     */
    function printAbsences($dayNumber, $hourNumber){
        $this->absencesTotalPerDay = 0;

        foreach ($this->absences as $absence) {
            if (($absence->hourNumber==$hourNumber) && ($absence->dayNumber==$dayNumber)) {
                $this->absencesTotalPerDay++;
            }
        }

        return $this->absencesTotalPerDay>0;
    }


    /**
     * Manages when to show or hide the scroll sidebar.
     */
    function toggleScroll(){
        $this->js("document.querySelector('html').classList.toggle('overflow-hidden')");
    }


    /**
     * Toggle the view of all absences
     */
    function toggleShowAllAbsences(){
        $this->viewAllAbsences = !$this->viewAllAbsences;
        $this->toggleScroll();
    }


    /**
     * Toggle the view of the add absence form
     */
    function toggleShowAddAbsence(){
        $this->viewAddAbsence = !$this->viewAddAbsence;
        $this->toggleScroll();
    } 
    
    
    /**
     * Toggle the view of the add absence form
     */
    function toggleShowEditAbsence(){
        $this->viewEditAbsence = !$this->viewEditAbsence;
        $this->toggleScroll();
    }  
    
    
    /**
     * Mount the component
     */
    function mount(){
        $this->getAllAbsences();
    }


    
}