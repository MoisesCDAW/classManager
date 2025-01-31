<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserManagerComponent extends Component
{

    // Data to register a new professor
    public $departments = [];
    public $departmentID = null;
    public $professorName = null;
    public $professorSurnames = null;
    public $professorEmail = null;


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
     * Allows adding a professor. Checks that the data is valid and that the email is unique.
     */
    function addProfessor(){

        // Validate input fields
        $this->validate([
            'departmentID' => 'required|exists:departments,id',
            'professorName' => 'required|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/|max:255', 
            'professorSurnames' => 'required|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/|max:255',
            'professorEmail' => 'required|regex:/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}$/|max:500|unique:users,email',
        ]);

        User::create([
            'department_id' => $this->departmentID,
            'name' => $this->professorName,
            'surnames' => $this->professorSurnames,
            'email' => $this->professorEmail,
            'password' => Hash::make('aA1$qwer'),
        ]);

        // Reset input fields to null after successful registration
        $this->departmentID = null;
        $this->professorName = null;
        $this->professorSurnames = null;
        $this->professorEmail = null;
    }

    /**
     * Mount the component
     */
    function mount(){
        $this->getDepartments();
    }
}
