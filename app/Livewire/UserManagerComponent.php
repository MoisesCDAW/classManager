<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Livewire\WithFileUploads;

class UserManagerComponent extends Component
{
    use WithFileUploads;

    // Data to register a new professors
    public $CSVFile = null;
    public $successfulUpload = false;


    // Data to register a new professor
    public $departments = [];
    public $departmentID = null;
    public $professorName = null;
    public $professorSurnames = null;
    public $email = '';


    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.user-manager-component')->layout('layouts.app');
    }


    /**
     * It is responsible for sending the email so that the professor can assign their own password.
     */
    public function sendPasswordResetLink(){

        // Sends a password reset link to the user's email
        $status = Password::sendResetLink(
            $this->only('email')
        );

        // Checks if the password reset link was successfully sent
        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        // Flash a success message to the session
        session()->flash('status', __($status));
    }


    /**
     * Manage the upload of a CSV file with new professor data to insert into the database.
     */
    public function uploadCSV(){
        $this->validate([
            'CSVFile' => 'required|file|mimes:csv,txt|max:10240',
        ]);
        
        // The file is moved from the temporary path to the application's local storage, and the path is saved to locate the file later
        $path = $this->CSVFile->storeAs('', time() . '.' . $this->CSVFile->getClientOriginalExtension(), 'public');
        $this->CSVFile = null;
        
        $row = 0; //Indicates the position of the file reader pointer.

        if (($manager = fopen(storage_path('app/public/'.$path), "r")) !== FALSE) {

            // Each line of the file is traversed, storing each one in an array where the delimiter of the string is ';'.
            while (($professor = fgetcsv($manager, null, ";")) !== FALSE) {

                if ($row==0) {
                    $row++;
                    continue;
                }

                // We check that the professor does not already exist in the database.
                $exist = DB::table('users')
                    ->where('email', $professor[3])
                    ->first();

                if(!$exist){
                    User::create([
                        'department_id' => $professor[0],
                        'name' => $professor[1],
                        'surnames' => $professor[2],
                        'email' => $professor[3],
                        'password' => Hash::make($professor[4]),
                        
                    ])->assignRole('professor');
            
                    $this->email = $professor[3];
                    $this->sendPasswordResetLink();
                }
            }

            fclose($manager); // Close the reading stream.
            unlink(storage_path('app/public/'.$path)); // Deletion of the CSV file from local storage.
            $this->successfulUpload = true;
            $this->email = null; // To clear the last record that remains as a leftover from the loop.
        }
    }


    /**
     * Gets all departments from the database.
     */
    function getDepartments(){
        $this->departments = DB::table('departments')->get();
    }


    /**
     * Allows adding a professor. Checks that the data is valid and that the email is unique.
     */
    function addProfessor(){

        // Validate input fields
        $this->validate([
            'departmentID' => 'required|exists:departments,id',
            'professorName' => 'required|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/|max:255', 
            'professorSurnames' => 'required|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/|max:255',
            'email' => 'required|regex:/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}$/|max:500|unique:users,email',
        ]);

        User::create([
            'department_id' => $this->departmentID,
            'name' => $this->professorName,
            'surnames' => $this->professorSurnames,
            'email' => $this->email,
            'password' => Hash::make('aA1$qwer'),
            
        ])->assignRole('professor');

        $this->sendPasswordResetLink();

        // Reset input fields to null after successful registration
        $this->departmentID = null;
        $this->professorName = null;
        $this->professorSurnames = null;
        $this->email = null;
    }

    /**
     * Mount the component
     */
    function mount(){
        $this->getDepartments();
    }
}
