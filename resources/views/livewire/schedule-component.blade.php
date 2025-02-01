<div wire:poll.10s="renderSchudele">
    <!-- component -->
    <div class="p-2">

        <div class="lg:w-7/12 md:w-9/12 sm:w-10/12 mx-auto my-4">
            <div class="bg-white shadow-lg shadow-gray-600 rounded-xl">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row justify-between p-5 gap-5 sm:gap-0">

                    <!-- Table description -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center w-full">
                            <div>
                                <h3 class="font-semibold text-gray-800">Registro de Ausencias</h3>
                                <p class="text-gray-500 text-sm">Detalles de inasistencias para cada día en cada hora.</p>
                                <select wire:click="getAllAbsencesAsec" wire:model="weekNumber" class="text-gray-500 text-sm font-bold mt-2 border border-gray-300 rounded-md">
                                    @foreach ($weeks as $week)
                                        <option class="text-gray-500 text-sm font-bold mt-2" value="{{$loop->index}}">{{$currentYear}} - Semana: {{$week[0]}} al {{$week[1]}}</option>               
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex gap-2 items-center">

                        <button
                            id="morning-shift"
                            class="flex items-center h-10 rounded border border-gray-300 py-2.5 px-3 text-center text-xs text-gray-500 transition-all hover:opacity-75 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            wire:click="morningShift"
                            type="button">
                            @if ($shift==="morning")
                                <span class="font-bold text-black">Turno Mañana</span>
                            @else
                                Turno Mañana
                            @endif
                        </button>
                    
                        <button
                            id="afternoon-shift"
                            class="flex items-center h-10 rounded border border-gray-300 py-2.5 px-3 text-center text-xs text-gray-500 transition-all hover:opacity-75 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            wire:click="afternoonShift"
                            type="button">
                            @if ($shift==="afternoon")
                                <span class="font-bold text-black">Turno Tarde</span>
                            @else
                                Turno Tarde
                            @endif
                        </button>

                        
                    </div>
                </div>

                {{-- Morning Schedule --}}
                <div class="grid grid-cols-[10%_1fr_1fr_1fr_1fr_1fr] gap-2 p-4 text-center w-full" id="morning-Schedule">
                    <div class="text-sm">Hora</div>
                    <div class="text-sm">Lunes</div>
                    <div class="text-sm">Martes</div>
                    <div class="text-sm">Miér.</div>
                    <div class="text-sm">Jueves</div>
                    <div class="text-sm">Viernes</div>

                    <!-- Rows and columns -->
                    @foreach ($shiftSchedule as $hour)
                        <div class="text-sm p-4 flex justify-center items-center h-[8vh]">{{$hour[0]}} <br> {{$hour[1]}}</div>
                        
                        @foreach ($days as $day)
            
                            {{-- First param: hourNumber, Second param: dayNumber--}}
                            @if ($this->printAbsences($loop->parent->index, $loop->index))

                                {{-- A link was applied at the top of the page (layout: app-blade) to control that no content is displayed outside the space occupied by the modal. --}}
                                <a href="#header-layout" wire:click="chooseAction({{true}}, {{$loop->parent->index}}, {{$loop->index}})" class="checkbox border-2 rounded-lg flex justify-center sm:block cursor-pointer" style="background-color: {{$hour[3]}}">
                                    <div class="rounded-lg w-7 h-6 sm:w-10 sm:h-8 m-2 text-sm flex items-center justify-center text-gray-500" style="background-color: {{$hour[2]}}">{{ $this->absencesTotalForDay }}</div>
                                </a>

                            @else
                                <a href="#header-layout" wire:click="chooseAction(@js(false), {{$loop->parent->index}}, {{$loop->index}})" class="checkbox border-2 rounded-lg flex justify-center sm:block cursor-pointer" style="background-color: {{$hour[3]}}"></a>
                            @endif

                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        
        <!-- MODAL CHOOSE ACTION START -->
        @if ($viewChooseAction)
            <div class="absolute inset-0 z-[10] grid place-items-center bg-black bg-opacity-10 backdrop-blur-sm transition-opacity duration-300">
                
                <!-- Modal body -->
                <div class="flex flex-col p-4 w-[80vw] h-[25vh] sm:w-[50vw] sm:h-[45vh] lg:w-[25vw] lg:h-[25vh] rounded-lg bg-gray-800 shadow-sm justify-center gap-4">
                    <div class="flex flex-col gap-2 w-full justify-center">
                        <button wire:click="toggleShowAddAbsence" class="rounded-md bg-gray-300 py-2 px-4 border border-transparent text-center text-sm text-black transition-all shadow-md hover:shadow-lg focus:bg-white focus:shadow-none active:bg-white hover:bg-white active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                            Crear Ausencia
                        </button>
                        
                        <button wire:click="toggleShowAllAbsences" class="rounded-md bg-[#CCCCFF] py-2 px-4 border border-transparent text-center text-sm text-black transition-all shadow-md hover:shadow-lg focus:bg-white focus:shadow-none active:bg-white hover:bg-white active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                            Ver todas las ausencias
                        </button>
                    </div>
                    
                    <div class="flex justify-center">
                        <button wire:click="chooseAction({{true}})" class="tracking-widest rounded-md bg-gray-800 py-2 px-4 border border-transparent text-center text-xs font-semibold text-gray-300 transition-all shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                            CANCELAR
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <!-- MODAL CHOOSE ACTION END -->


        <!-- MODAL ADD START -->
        @if ($viewAddAbsence)
            <div class="absolute inset-0 z-[10] grid place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 ">
                
                <!-- Modal body -->
                @role("admin")
                    <div class="flex flex-col p-4 w-[90vw] h-[90vh] rounded-lg bg-gray-800 shadow-sm ">
                        <div class="flex flex-col gap-4 w-full overflow-y-scroll lg:overflow-y-hidden">
                            <label>
                                <select class="text-gray-500 text-sm font-bold mt-2 border border-gray-300 rounded-md" wire:model="professorDepartment">
                                    <option>Seleccionar departamento</option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                                @error('professorDepartment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </label>

                            <div class="flex gap-4 flex-col sm:flex-row">
                                <label class="w-full lg:w-[25vw]">
                                    <span class="text-sm text-gray-500">Nombre del profesor<span> <br>
                                    <input wire:model="professorName" type="text" class="w-full bg-gray-900 border-gray-700 rounded-md p-2 mt-3 text-white">
                                    @error('professorName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </label>
                                
                                <label class="w-full lg:w-[25vw]">
                                    <span class="text-sm text-gray-500">Apellidos del profesor<span> <br>
                                    <input wire:model="professorSurnames" type="text" class="w-full bg-gray-900 border-gray-700 rounded-md p-2 mt-3 text-white">
                                    @error('professorSurnames') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </label>
                            </div>

                            {{-- Comment --}}
                            <textarea wire:model="commentAbsence" class="text-sm text-white w-full min-h-[35vh] sm:min-h-[60vh] lg:min-h-[40vh] bg-gray-900 border-gray-700 rounded-md" placeholder="Añade un comentario para crear la ausencia..."></textarea>
                            @error('commentAbsence') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div> 

                        
                        <!-- Modal buttons -->
                        <div class="flex items-center pt-4 justify-end">
                            <button wire:click="toggleShowAddAbsence({{true}})" class="tracking-widest rounded-md bg-gray-800 py-2 px-4 border border-transparent text-center text-xs font-semibold text-gray-300 transition-all shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                CANCELAR
                            </button>
                            <button wire:click="addAbsence" class="tracking-widest rounded-md bg-gray-300 py-2 px-4 border border-transparent text-center text-xs font-semibold text-black transition-all shadow-md hover:shadow-lg focus:bg-white focus:shadow-none active:bg-white hover:bg-white active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                ENVIAR
                            </button>
                        </div>
                    </div> 
                @else
                    <div class="flex flex-col p-4 w-[90vw] h-[70vh] rounded-lg bg-gray-800 shadow-sm ">
                        <div class="flex flex-col gap-4 w-full overflow-y-scroll md:overflow-y-hidden">
                            {{-- Comment --}}
                            <textarea wire:model="commentAbsence" class="text-sm text-white w-full min-h-[50vh] sm:min-h-[70vh] lg:min-h-[50vh] bg-gray-900 border-gray-700 rounded-md" placeholder="Añade un comentario para crear la ausencia..."></textarea>
                            @error('commentAbsence') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>  
                    

                        <!-- Modal buttons -->
                        <div class="flex items-center pt-4 justify-end">
                            <button wire:click="toggleShowAddAbsence({{true}})" class="tracking-widest rounded-md bg-gray-800 py-2 px-4 border border-transparent text-center text-xs font-semibold text-gray-300 transition-all shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                CANCELAR
                            </button>
                            <button wire:click="addAbsence" class="tracking-widest rounded-md bg-gray-300 py-2 px-4 border border-transparent text-center text-xs font-semibold text-black transition-all shadow-md hover:shadow-lg focus:bg-white focus:shadow-none active:bg-white hover:bg-white active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                ENVIAR
                            </button>
                        </div>
                    </div>
                @endrole
            </div>
        @endif
        <!-- MODAL ADD END -->


        <!-- MODAL VIEW ALL START -->
        @if ($viewAllAbsences)     
            <div class="absolute inset-0 z-[10] grid h-screen w-full place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 ">
                <div class="relative m-4 p-4 w-[90vw] h-[92vh] rounded-lg bg-gray-800 shadow-sm ">

                    <!-- Modal header -->
                    <div class="flex shrink-0 items-center justify-between pb-4 font-medium text-slate-800">

                        {{-- Day and time block --}}
                        <p class="text-lg text-slate-300">{{$days[$dayNumber]}} / {{$morningSchedule[$hourNumber][0]}} a {{$morningSchedule[$hourNumber][1]}}</p>

                        {{-- Order buttons --}}
                        @if ($orderDesc)
                            <button wire:click="orderByDesc" class="tracking-widest rounded-md border border-transparent py-2 px-4 text-center text-xs font-semibold transition-all text-gray-300 shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                ORDENAR DESCENDENTE
                            </button>
                        @endif

                        @if ($orderAsc)
                            <button wire:click="orderByAsc" class="tracking-widest rounded-md border border-transparent py-2 px-4 text-center text-xs font-semibold transition-all text-gray-300 shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                ORDENAR ASCENDENTE
                            </button>
                        @endif

                    </div>

                    <!-- Modal body -->
                    <div class="flex pe-4 flex-col gap-4 h-[65vh] sm:h-[55vh] lg:h-[70vh] border-slate-200 py-4 leading-normal text-slate-600 font-light overflow-y-scroll">

                        @foreach ($professors as $professor) 
                            <!-- Card -->
                            <div class="w-full p-6 bg-white border rounded-lg shadow-lg">
                                <div class="flex justify-between items-start">

                                    {{-- Professor Name --}}
                                    <h3 class="sm:text-xl font-semibold">{{$professor[0]->name}}</h3>

                                    @if(auth()->user()->hasRole('admin') || $this->checkTimeToEdit($absencesForDayAndHour[$loop->index]))
                                        <div class="flex gap-4">
                                            {{-- Edit button --}}
                                            <button wire:click="toggleShowEditAbsence(@js($absencesForDayAndHour[$loop->index]), @js($professor[0]))" class="p-1 text-gray-500 transition-colors rounded hover:bg-gray-100 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="Filled" viewBox="0 0 24 24" width="16" height="16" fill="#000"><path d="M1.172,19.119A4,4,0,0,0,0,21.947V24H2.053a4,4,0,0,0,2.828-1.172L18.224,9.485,14.515,5.776Z"/><path d="M23.145.855a2.622,2.622,0,0,0-3.71,0L15.929,4.362l3.709,3.709,3.507-3.506A2.622,2.622,0,0,0,23.145.855Z"/></svg>
                                            </button>
                                            
                                            {{-- Delete button --}}
                                            <button wire:click="deleteAbsence(@js($absencesForDayAndHour[$loop->index]))" wire:confirm="¿Eliminar Ausencia?" class="p-1 text-gray-500 transition-colors rounded hover:bg-gray-100 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" width="16" height="16" viewBox="0 0 24 24" fill="#000"><path d="m22,4h-4.101c-.465-2.279-2.484-4-4.899-4h-2c-2.414,0-4.434,1.721-4.899,4H2c-.552,0-1,.447-1,1s.448,1,1,1h.86l1.296,13.479c.248,2.578,2.388,4.521,4.977,4.521h5.727c2.593,0,4.733-1.947,4.978-4.528l1.276-13.472h.885c.552,0,1-.447,1-1s-.448-1-1-1Zm-11-2h2c1.302,0,2.402.839,2.816,2h-7.631c.414-1.161,1.514-2,2.816-2Zm4.707,14.293c.391.391.391,1.023,0,1.414-.195.195-.451.293-.707.293s-.512-.098-.707-.293l-2.293-2.293-2.293,2.293c-.195.195-.451.293-.707.293s-.512-.098-.707-.293c-.391-.391-.391-1.023,0-1.414l2.293-2.293-2.293-2.293c-.391-.391-.391-1.023,0-1.414s1.023-.391,1.414,0l2.293,2.293,2.293-2.293c.391-.391,1.023-.391,1.414,0s.391,1.023,0,1.414l-2.293,2.293,2.293,2.293Z"/></svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                {{-- Comment --}}
                                <p class="mt-4 text-gray-600 max-w-[90vw] line-clamp-3">{{$absencesForDayAndHour[$loop->index]->comment}}</p>
                                
                                {{-- Department and publication date --}}
                                <div class="flex items-center justify-between mt-6">
                                    <span class="ml-1 text-sm text-gray-600">{{$professor[1]->name}}</span>
                                    <span class="ml-1 text-sm text-gray-600">Publicado: {{$absencesForDayAndHour[$loop->index]->created_at}}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                <!-- Modal buttons -->
                <div class="flex shrink-0 flex-wrap items-center pt-4 justify-end">
                    <button wire:click="toggleShowAllAbsences({{true}})" class="tracking-widest rounded-md bg-gray-800 py-2 px-4 border border-transparent text-center text-xs font-semibold text-gray-300 transition-all shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                        CERRAR
                    </button>
                </div>
            </div>
        @endif
        <!-- MODAL VIEW END -->


        <!-- MODAL EDIT START -->
        @if ($viewEditAbsence)
            <div class="absolute inset-0 z-[10] grid place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 ">
                
                <!-- Modal body -->
                <div class="flex flex-col p-4 w-[90vw] h-[70vh] rounded-lg bg-gray-800 shadow-sm ">
                    <div class="flex flex-col gap-4 w-full overflow-y-scroll md:overflow-y-hidden">
                        {{-- Comment --}}
                        <textarea wire:model="commentEdit" class="text-sm text-white w-full min-h-[50vh] sm:min-h-[70vh] lg:min-h-[50vh] bg-gray-900 border-gray-700 rounded-md" placeholder="Añade un comentario...">{{$this->commentEdit}}</textarea>
                        @error('commentEdit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>  
                

                    <!-- Modal buttons -->
                    <div class="flex items-center pt-4 justify-end">
                        <button wire:click="toggleShowEditAbsence" class="tracking-widest rounded-md bg-gray-800 py-2 px-4 border border-transparent text-center text-xs font-semibold text-gray-300 transition-all shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                            CANCELAR
                        </button>
                        <button wire:click="editAbsence" class="tracking-widest rounded-md bg-gray-300 py-2 px-4 border border-transparent text-center text-xs font-semibold text-black transition-all shadow-md hover:shadow-lg focus:bg-white focus:shadow-none active:bg-white hover:bg-white active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                            EDITAR
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <!-- MODAL EDIT END -->
        
    </div>
</div>