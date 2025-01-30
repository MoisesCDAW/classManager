<?php

namespace App\Livewire;

use App\Models\Absence;
use Illuminate\Support\Facades\Auth;
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
    public $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];


    /**
     * All absences of the school
     */
    public $absences = [];

    /**
     * Absences total per day, It will be calculated dynamically based on the day the loop pointer is at in the view of this component.
     */
    public $absencesTotalForDay = 0;

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
     * True to show the choose action buttons.
     */
    public $viewChooseAction = false;

    /**
     * Comment that is being edited when opening the "EditComment" modal
     */
    public $commentEdit = null;

    /**
     * The hour number and day number of the absence
     */
    public $hourNumber = null;
    public $dayNumber = null;

    /**
     * Order by to show the absences
     */
    public $orderDesc = true;
    public $orderAsc = false;

    /**
     * Professors, absences and departments for a specific day and hour
     */
    public $professors = [];
    public $absencesForDayAndHour = [];

    /**
     * It allows controlling the time during which a non-admin user can edit or delete an absence. This applies only to the absences created by the user.
     */
    public $timeToEdit = false;

    /**
     * Data to Add model
     */
    public $departments = [];
    public $commentAbsence = null;
    public $professorDepartment = null;
    public $professorName = null;
    public $professorSurnames = null;



    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.schedule-component');
    }


    /**
     * Get all absences ordered by hour number ascending
     */	
    function getAllAbsencesAsec() {
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
    function printAbsences($hourNumber, $dayNumber){
        $this->absencesTotalForDay = 0;

        foreach ($this->absences as $absence) {
            if (($absence->hourNumber==$hourNumber) && ($absence->dayNumber==$dayNumber)) {
                $this->absencesTotalForDay++;
            }
        }

        return $this->absencesTotalForDay>0;
    }


    /**
     * Find all the absences that match the hour and day passed as parameters, along with their respective user and department.
     */
    function getAbsencesForDayAndHour(){
        // Reset the professors and absences
        $this->professors = [];
        $this->absencesForDayAndHour = [];

        // Find all absences that match the hour and day passed as parameters
        $this->absencesForDayAndHour = DB::table('absences')
                ->where('hourNumber', $this->hourNumber)
                ->where('dayNumber', $this->dayNumber)
                ->get();

        // Find the user and department of each absence
        foreach ($this->absencesForDayAndHour as $absence) {
            $professor = DB::table('users')
            ->where('id', $absence->user_id)
            ->first();

            $department = DB::table('departments')
                ->where('id', $professor->department_id)
                ->first();
            
            array_push($this->professors, [$professor, $department]);
        }
    }


    public function morningSchedule(){
        
        $this->js("
            const element = document.querySelector('#morning-shift');
            element.classList.toggle('outline');
            element.classList.toggle('outline-3');
            element.classList.toggle('outline-gray-600');
        ");
    }


    /**
     * Manages when to show or hide the scroll sidebar.
     */
    function toggleScroll(){
        $this->js("document.querySelector('html').classList.toggle('overflow-hidden')");
    }


    /**
     * This function is used to display a modal where the user can select the action they want to perform: 
     * either adding an absence or viewing all absences for that day. It is only used when there is an absence on the day the click was made.
     */
    public function chooseAction($both, $hourNumber=null, $dayNumber=null){
        $this->hourNumber = $hourNumber;
        $this->dayNumber = $dayNumber;
        $this->js("document.querySelector('html').classList.toggle('overflow-hidden')");

        if ($both) {
            $this->viewChooseAction = !$this->viewChooseAction;    
        }else {
            $this->toggleShowAddAbsence();
        }

    }


    /**
     * Toggle the view of all absences
     */
    function toggleShowAllAbsences($scroll=false){
        $this->viewAllAbsences = !$this->viewAllAbsences;
        $this->viewChooseAction = false; 
        $this->getAbsencesForDayAndHour();

        if ($scroll) {
            $this->toggleScroll();
        }
        
    }


    /**
     * Toggle the view of the add absence form
     */
    function toggleShowAddAbsence($scroll=false){
        $this->viewAddAbsence = !$this->viewAddAbsence;
        $this->viewChooseAction = false;

        $this->js("document.querySelector('html').classList.add('overflow-hidden')");

        if ($scroll) {
            $this->js("document.querySelector('html').classList.remove('overflow-hidden')");
            $this->professorDepartment = null;
            $this->commentAbsence = null;
            $this->professorName = null;
            $this->professorSurnames = null;
        }
    } 
    
    
    /**
     * Toggle the view of the add absence form
     */
    function toggleShowEditAbsence($comment=null){
        $this->commentEdit = $comment;
        $this->viewEditAbsence = !$this->viewEditAbsence;
    }  


    /**
     * Order the absences by descending
     */
    function orderByDesc(){
        $this->orderDesc = false;
        $this->orderAsc = true;
    }


    /**
     * Order the absences by ascending
     */
    function orderByAsc(){
        $this->orderDesc = true;
        $this->orderAsc = false;
    }
    

    /**
     * Check if the time to edit the absence has passed
     */
    function checkTimeToEdit($absence){
        $onTime = false;
        $currentTime = date_create();
        $absenceTime = date_create($absence->created_at);
        $timeToEdit = 10; // 10 minutes
        $interval = $currentTime->diff($absenceTime);

        $days = $interval->format("%a");
        $hours = $interval->format("%R%h");
        $minutes = $interval->format("%i");

        if ($days == 0 && $hours == 0 && $minutes <= $timeToEdit) {
            $onTime = true;
        }

        return $onTime;
    }


    /**
     * Gets all departments from the database.
     */
    function getDepartments(){
        $this->departments = DB::table('departments')->get();
    }


    /**
     * Add an absence
     */
    function addAbsence() {

        $admin = [
            'professorDepartment' => 'required|exists:departments,id',
            'professorName' => 'required|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/|max:255', 
            'professorSurnames' => 'required|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/|max:255',
            'commentAbsence' => 'required|regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚ\s]+$/|min:10|max:500'
        ];

        $professor = [
            'commentAbsence' => 'required|regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚ\s]+$/|min:10|max:500'
        ];


        if (auth()->user()->hasRole('admin')) {

            $this->validate($admin);

            $exist = DB::table('users')
            ->where('department_id', $this->professorDepartment)
            ->where('name', $this->professorName)
            ->where('surnames', $this->professorSurnames)
            ->first();

            if ($exist) {
                Absence::create([
                    'user_id' => $exist->id,
                    'comment' => $this->commentAbsence,
                    'startHour' => $this->morningSchedule[$this->hourNumber][0],
                    'endHour' => $this->morningSchedule[$this->hourNumber][1],
                    'hourNumber' => $this->hourNumber,
                    'dayNumber' => $this->dayNumber
                ]);

            $this->toggleShowAddAbsence(true);
            $this->renderAbsences();

            }else{
                // ...
            }

        }
        

        if (auth()->user()->hasRole('professor')){
            $this->validate($professor);

            Absence::create([
                'user_id' => Auth::id(),
                'comment' => $this->commentAbsence,
                'startHour' => $this->morningSchedule[$this->hourNumber][0],
                'endHour' => $this->morningSchedule[$this->hourNumber][1],
                'hourNumber' => $this->hourNumber,
                'dayNumber' => $this->dayNumber
            ]);

            $this->toggleShowAddAbsence(true);
            $this->renderAbsences();
        }
    }


    /**
     * Edit an absence
     */
    function editAbsence(){
        
    }


    /**
     * Mount the component
     */
    function renderAbsences(){
        $this->getAllAbsencesAsec();
        $this->getDepartments();
    }


    /**
     * Mount the component
     */
    function mount(){
        $this->getAllAbsencesAsec();
        $this->getDepartments();
    }
}


