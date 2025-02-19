<?php

namespace App\Livewire;

use App\Jobs\ProfessorsEmailJob;
use App\Models\Absence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
     * Afternoon schedule, The hour numbers will be saved, with a start and end pair considered a single block.
     */
    public $afternoonSchedule = [
        [
            '14:00', // Start hour
            '14:55', // End hour
            '#CCFFFF', // Item color
            '#FFF' // background color
        ],
        ['14:55', '15:45', '#CCFFCC', '#FFF'],
        ['15:45', '16:45', '#CCCCFF', '#FFF'],
        ['16:45', '17:15', '#FFFFFF', '#DDD'],
        ['17:15', '18:10', '#FFCCFF', '#FFF'],
        ['18:10', '19:05', '#FFFF99', '#FFF'],
        ['19:05', '20:00', '#DFDFDF', '#FFF'],
    ];

    /**
     * Determine which schedule should be displayed between the morning shift and the afternoon shift.
     */
    public $shiftSchedule = [];
    public $shift = '';

    /**
     * schedule days, The days start at 0 with Monday being the first day.
     */
    public $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

    public $weekNumber = 0;

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
     * indicates whether an absence has already been added on a date and time
     */
    public $addedAbsence = false;

    /**
     * Data to show weeks in the schedule dropdown
     */
    public $currentYear = null;
    public $weeks = [];
    public $maxWeeks = 4;

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
     * Professors and absences for a specific day and hour
     */
    public $professors = [];
    public $absencesForDayAndHour = [];

    /**
     * It allows controlling the time during which a non-admin user can edit or delete an absence. This applies only to the absences created by the user.
     */
    public $timeToEdit = 10; // 10 min

    /**
     * Data to Add model
     */
    public $departments = [];
    public $commentAbsence = null;
    public $professorDepartment = null;
    public $professorName = null;
    public $professorSurnames = null;

    /**
     * Data to Edit model
     */
    public $professorEdit = null;
    public $commentEdit = null;
    public $absenceEditID = null;


    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.schedule-component');
    }


    /**
     * "Render the schedule view with the updated data after the update time marked in the wire:poll." 
     */
    function renderSchudele(){

        if ($this->orderDesc) {
            $this->getAllAbsencesAsec("desc");
            $this->getAbsencesForDayAndHour("desc");
        }else{
            $this->getAllAbsencesAsec();
            $this->getAbsencesForDayAndHour();
        }
    }


    /**
     * Get all absences ordered by hour number ascending
     */	
    function getAllAbsencesAsec($order = "asc") {
        $absences = DB::table('absences')
                ->orderBy('hourNumber', $order)
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
            if (($absence->hourNumber==$hourNumber) && ($absence->dayNumber==$dayNumber) && ($absence->week==$this->weekNumber) && ($absence->shift==$this->shift)) {
                $this->absencesTotalForDay++;
            }
        }

        return $this->absencesTotalForDay>0;
    }


    /**
     * Find all the absences that match the hour and day passed as parameters, along with their respective user and department.
     */
    function getAbsencesForDayAndHour($order="asc"){
        // Reset the professors and absences
        $this->professors = [];
        $this->absencesForDayAndHour = [];

        // Find all absences that match the hour and day passed as parameters
        $this->absencesForDayAndHour = DB::table('absences')
                ->where('hourNumber', $this->hourNumber)
                ->where('dayNumber', $this->dayNumber)
                ->where('week', $this->weekNumber)
                ->where('shift', $this->shift)
                ->orderBy('created_at', $order)
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

            if ($this->checkAddedAbsence()) {
                $this->toggleShowAllAbsences();
            }else{
                $this->viewChooseAction = !$this->viewChooseAction;    
            }
        }else {
            $this->toggleShowAddAbsence();
        }

    }

    /**
     * Allows closing the "chooseAction" modal when clicking the "cancel" button.
     */
    function closeChooseAction(){
        $this->viewChooseAction = false;   
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
    function toggleShowEditAbsence($absence=null, $user=null){

        if ($absence && $user) {  
            $this->commentEdit = $absence["comment"];
            $this->professorEdit = $user;
            $this->absenceEditID = $absence["id"];
        }
        $this->viewEditAbsence = !$this->viewEditAbsence;
    }  


    /**
     * Order the absences by descending
     */
    function orderByDesc(){
        $this->orderDesc = false;
        $this->orderAsc = true;
        $this->getAbsencesForDayAndHour("desc");
    }


    /**
     * Order the absences by ascending
     */
    function orderByAsc(){
        $this->orderDesc = true;
        $this->orderAsc = false;
        $this->getAbsencesForDayAndHour();
    }
    

    /**
     * This function compares the current time with the time the absence was created 
     * to determine if the user is still within the allowed time frame to edit the absence.
     */
    function checkTimeToEdit($absence){
        $onTime = false; // Initialize the variable to track if the time to edit has passed

        if ($absence->user_id==Auth::user()->id) {
            $currentTime = date_create(); // Get the current date and time
            $absenceTime = date_create($absence->created_at); // Create a DateTime object from the absence's creation timestamp
            $interval = $currentTime->diff($absenceTime); // Calculate the interval between the current time and the absence creation time
    
            // Extract the number of days, hours, and minutes from the interval
            $days = $interval->format("%a");
            $hours = $interval->format("%R%h");
            $minutes = $interval->format("%i");
    
            // Check if the absence was created today and if the time to edit (in minutes) is still within the allowed limit
            if ($days == 0 && $hours == 0 && $minutes <= $this->timeToEdit) {
                $onTime = true; // The time to edit the absence is still valid
            }
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
        

        // Validations that will be applied depending on the type of form to be used. The form type depends on the session user's role
        $admin = [
            'professorDepartment' => 'required|exists:departments,id',
            'professorName' => 'required|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/|max:255|exists:users,name', 
            'professorSurnames' => 'required|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/|max:255|exists:users,surnames',
            'commentAbsence' => 'required|regex:/^[A-Za-z0-9áéíóúñÁÉÍÓÚÑ\s\.\,\(\)\?\¿\!\¡\-\_\:\;]+$/|min:10|max:500'
        ];

        $professor = [
            'commentAbsence' => 'required|regex:/^[A-Za-z0-9áéíóúñÁÉÍÓÚÑ\s\.\,\(\)\?\¿\!\¡\-\_\:\;]+$/|min:10|max:500'
        ];


        
        /** 
         * ADMIN
         */
        if (auth()->user()->hasRole('admin')) {

            $this->validate($admin);
            

            // It is checked whether the professor belongs to that department. 
            $exist = DB::table('users')
            ->where('department_id', $this->professorDepartment)
            ->where('name', $this->professorName)
            ->where('surnames', $this->professorSurnames)
            ->first();

            if ($exist) {

                if (!$this->checkAddedAbsence($exist->id)) {
                    
                    $absence = Absence::create([
                        'user_id' => $exist->id,
                        'comment' => $this->commentAbsence,
                        'startHour' => $this->shiftSchedule[$this->hourNumber][0],
                        'endHour' => $this->shiftSchedule[$this->hourNumber][1],
                        'hourNumber' => $this->hourNumber,
                        'dayNumber' => $this->dayNumber,
                        'week' => $this->weekNumber,
                        'shift' => $this->shift,
                    ]);
                
                    dispatch(new ProfessorsEmailJob($exist, $absence, $this->weeks[$this->weekNumber], $this->days[$this->dayNumber]));
                    
                    $this->toggleShowAddAbsence(true);
                    $this->getAllAbsencesAsec();
                }else {
                    throw ValidationException::withMessages([
                        'professorDepartment' => ['Ese profesor ya tiene una ausencia para ese día a esa hora'],
                    ]);
                }
                
            }else {
                throw ValidationException::withMessages([
                    'professorDepartment' => ['El profesor no pertenece a ese departamento'],
                ]);
            }
        }
        
        /** 
         * PROFESSORS
         */
        if (auth()->user()->hasRole('professor')){
            $this->validate($professor);

            $absence = Absence::create([
                'user_id' => Auth::id(),
                'comment' => $this->commentAbsence,
                'startHour' => $this->shiftSchedule[$this->hourNumber][0],
                'endHour' => $this->shiftSchedule[$this->hourNumber][1],
                'hourNumber' => $this->hourNumber,
                'dayNumber' => $this->dayNumber,
                'week' => $this->weekNumber,
                'shift' => $this->shift,
            ]);

            dispatch(new ProfessorsEmailJob(Auth::user(), $absence, $this->weeks[$this->weekNumber], $this->days[$this->dayNumber]));

            $this->toggleShowAddAbsence(true);
            $this->getAllAbsencesAsec();
        }
    }


    /**
     * Edit an absence
     */
    function editAbsence(){
        $this->validate([
            'commentEdit' => 'required|regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚ\s\.\,\(\)\?\¿\!\¡]+$/|min:10|max:500'
        ]);

        DB::table('absences')
            ->where('id', $this->absenceEditID)
            ->update(['comment' => $this->commentEdit]);

        $this->toggleShowEditAbsence(true);
        $this->getAbsencesForDayAndHour();
    }


    /**
     * Delete ab absence
     */
    function deleteAbsence($absenceID){
        DB::table('absences')
            ->where('id', $absenceID)
            ->delete();

        $this->toggleShowAllAbsences(true);
        $this->getAllAbsencesAsec();
    }


    /**
     * Get the current dates corresponding to each week. Only a range of up to 4 weeks is calculated.
     */
    function getDateSchedule(){
        $startDate = date_create();
        $endDate = null;
        $this->currentYear = date_format($startDate, 'Y');

        /* We calculate how many days need to be subtracted from the current day to make it Monday, 
        so we can determine the start date of the current week. */
        $currentNumberDay = date_format(date_create(), 'N');
        $dayCounter = 0;
        for ($i=$currentNumberDay; $i > 0 ; $i--) { 
            if ($i!=1) {
                $dayCounter++;
            }
        }

        // Get Start of current week
        date_modify($startDate, "-$dayCounter day");
        $endDate = clone $startDate;
        date_modify($endDate, "+4 day");

        // The first week is saved
        array_push($this->weeks, [date_format($startDate, 'd/m'), date_format($endDate, 'd/m')]);
        
        // The remaining weeks are obtained to complete the maximum number of weeks
        for ($i=$this->maxWeeks-1; $i > 0; $i--) { 
            date_modify($startDate, "+7 day");
            $endDate = clone $startDate;
            date_modify($endDate, "+4 day");
    
            array_push($this->weeks, [date_format($startDate, 'd/m'), date_format($endDate, 'd/m')]);
        }
    }


    /**
     * Manages how different modals are displayed. If the user in the current session has already added an 
     * absence for that specific time, day, and week, the "ViewAllAbsences" modal will be shown directly to prevent adding another absence.
     */
    function checkAddedAbsence($user = null){
        
        if (!$user) {
            $user = Auth::id();
        }
        
        $addedAbsence = DB::table('absences')
        ->where('user_id', $user)
        ->where('startHour', $this->shiftSchedule[$this->hourNumber][0])
        ->where('endHour', $this->shiftSchedule[$this->hourNumber][1])
        ->where('hourNumber', $this->hourNumber)
        ->where('dayNumber', $this->dayNumber)
        ->where('week', $this->weekNumber)
        ->where('shift', $this->shift)
        ->first();
        
        return $addedAbsence;   
    }


    /**
     * Allows selecting and displaying the afternoon shift schedule.
     */
    function afternoonShift(){
        $this->shiftSchedule = $this->afternoonSchedule;
        $this->shift = "afternoon";
        $this->getAllAbsencesAsec();
    }


    /**
     * Allows selecting and displaying the morning shift schedule.
     */
    function morningShift(){
        $this->shiftSchedule = $this->morningSchedule;
        $this->shift = "morning";
        $this->getAllAbsencesAsec();
    }


    /**
     * Mount the component
     */
    function mount(){
        $this->shiftSchedule = $this->afternoonSchedule;
        $this->shift = "afternoon";
        $this->getDateSchedule();
        $this->getAllAbsencesAsec();
        $this->getDepartments();
    }
}


