<?php

namespace App\Livewire;

use Livewire\Component;

class ScheduleComponent extends Component
{

    public $morningSchedule = [
        ['8:00', '8:55', '#CCFFFF'],
        ['8:55', '9:50', '#CCFFCC'],
        ['9:50', '10:45', '#CCCCFF'],
        ['10:45', '11:15', '#ddd'], // Break in index 3
        ['11:15', '12:10', '#FFCCFF'],
        ['12:10', '13:05', '#FFFF99'],
        ['13:05', '14:00', '#dfdfdF'],
    ];

    public function render()
    {
        return view('livewire.schedule-component');
    }
}
