<?php

namespace App\Livewire;

use App\Mail\ProfessorsMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Facades\Password;

class UserManagerComponent extends Component
{

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
     * Gets all departments from the database.
     */
    function getDepartments(){
        $this->departments = DB::table('departments')->get();
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
        ]);

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
