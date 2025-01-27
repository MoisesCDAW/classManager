<div>
    <!-- component -->
    <div class="p-2">

        <div class="lg:w-7/12 md:w-9/12 sm:w-10/12 mx-auto my-4">
            <div class="bg-white shadow-lg shadow-gray-600 rounded-xl">

                <div class="flex flex-col sm:flex-row justify-between p-5 gap-5 sm:gap-0">

                    <!-- Table description -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center w-full">
                            <div>
                                <h3 class="font-semibold text-gray-800">Registro de Ausencias</h3>
                                <p class="text-gray-500 text-sm">Detalles de inasistencias para cada día en cada hora.</p>
                                <select class="text-gray-500 text-sm font-bold mt-2 border border-gray-300 rounded-md">
                                    <option class="text-gray-500 text-sm font-bold mt-2">2025 - Semana: 20/01 al 24/01</option>
                                    <option class="text-gray-500 text-sm font-bold mt-2">2025 - Semana: 27/01 al 31/01</option>
                                    <option class="text-gray-500 text-sm font-bold mt-2">2025 - Semana: 03/02 al 07/01</option>
                                    <option class="text-gray-500 text-sm font-bold mt-2">2025 - Semana: 10/02 al 14/01</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex gap-2 items-center">
                        <button
                            class="flex items-center h-10 rounded border border-gray-300 py-2.5 px-3 text-center text-xs font-semibold text-gray-600 transition-all hover:opacity-75 focus:ring focus:ring-gray-300 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            type="button">
                            Turno Mañana
                        </button>
                    
                        <button
                            class="flex items-center h-10 rounded border border-gray-300 py-2.5 px-3 text-center text-xs font-semibold text-gray-600 transition-all hover:opacity-75 focus:ring focus:ring-gray-300 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            type="button">
                            Turno Tarde
                        </button>

                        <a
                            href="#app-blade"
                            wire:click="toggleShowAddAbsence"
                            class="h-10 flex select-none items-center gap-2 rounded bg-gray-800 py-2.5 px-4 text-xs font-semibold text-white shadow-md shadow-gray-900/10 transition-all hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                            type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" class="w-3 h-3" fill="#fff"><g><path d="M480,224H288V32c0-17.673-14.327-32-32-32s-32,14.327-32,32v192H32c-17.673,0-32,14.327-32,32s14.327,32,32,32h192v192   c0,17.673,14.327,32,32,32s32-14.327,32-32V288h192c17.673,0,32-14.327,32-32S497.673,224,480,224z"/></g></svg>
                            Agregar Ausencia
                        </a>
                    </div>
                </div>

                {{-- Schedule --}}
                <div class="grid grid-cols-[10%_1fr_1fr_1fr_1fr_1fr] gap-2 p-4 text-center w-full" id="calendar">
                    <div class="text-sm">Hora</div>
                    <div class="text-sm">Lunes</div>
                    <div class="text-sm">Martes</div>
                    <div class="text-sm">Miér.</div>
                    <div class="text-sm">Jueves</div>
                    <div class="text-sm">Viernes</div>

                    <!-- Rows and columns -->
                    @foreach ($morningSchedule as $hour)
                        <div class="text-sm p-4 flex justify-center items-center h-[8vh]">{{$hour[0]}} <br> {{$hour[1]}}</div>
                        
                        @foreach ($days as $day)
                            <div class="border-2 rounded-lg flex justify-center sm:block" style="background-color: {{$hour[3]}}">

                                {{-- First param: hourNumber, Second param: dayNumber--}}
                                @if ($this->printAbsences($loop->parent->index, $loop->index))

                                    {{-- A link was applied at the top of the page (layout: app-blade) to control that no content is displayed outside the space occupied by the modal. --}}
                                    <a href="#app-blade" wire:click="toggleShowAllAbsences({{$loop->parent->index}}, {{$loop->index}})" class="rounded-lg w-7 h-6 sm:w-10 sm:h-8 m-2 text-sm flex items-center justify-center text-gray-500 cursor-pointer" style="background-color: {{$hour[2]}}">{{ $this->absencesTotalForDay }}</a>
                                @endif

                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <!-- MODAL ADD START -->
        @if ($viewAddAbsence)
            <div class="text-gray-300 absolute inset-0 z-[20] grid h-screen w-full place-items-center bg-gray-900 bg-opacity-90 backdrop-blur-sm transition-opacity duration-300">
                <div class="relative m-4 p-4 w-[90vw] h-[90vh] rounded-lg bg-gray-800 shadow-sm">

                    <!-- Modal header -->
                    <div class="flex shrink-0 items-center justify-between pb-4 font-medium text-slate-800">
                        <p class="text-lg">Añadir ausencia</p>
                    </div>

                    <!-- Modal comments -->
                    <div class="flex flex-col gap-10 h-[70vh] sm:h-[55vh] lg:h-[70vh] w-full overflow-y-scroll pb-10 items-center">

                        <!-- Vista Admin -->
                        @role("admin")
                            <div class="flex flex-col gap-4 w-[70vw] sm:w-[45vw]">
                                <label>
                                    <span class="text-sm text-gray-500">** Selecciona el departamento para poder ingresar el nombre y apellidos del profesor<span> <br>
                                    <select class="text-gray-500 text-sm font-bold mt-2 border border-gray-300 rounded-md">
                                        <option>Seleccionar departamento</option>
                                        <option>Informática</option>
                                        <option>Idiomas</option>
                                        <option>Ciencias</option>
                                    </select>
                                </label>

                                {{-- <div class="flex flex-col lg:flex-row gap-5 justify-center"> --}}
                                    <label class="w-full">
                                        <span class="text-sm text-gray-500">Nombre del profesor<span> <br>
                                        <input type="text" class="w-full bg-gray-900 border-gray-700 rounded-md p-2 py-3 mt-3 text-white" disabled>
                                    </label>
                                    
                                    <label class="w-full">
                                        <span class="text-sm text-gray-500">Apellidos del profesor<span> <br>
                                        <input type="text" class="w-full bg-gray-900 border-gray-700 rounded-md p-2 py-3 mt-3 text-white" disabled>
                                    </label>
                                {{-- </div> --}}
                            </div>
                        @endrole

                        {{-- Schedule Date --}}
                        <label class="w-[70vw] sm:w-[45vw]">
                            <span class="text-sm text-gray-500">Especifica la fecha de la falta<span> <br>
                            <select class="text-gray-500 text-sm font-bold mt-2 border border-gray-300 rounded-md">
                                <option class="text-gray-500 text-sm font-bold mt-2">2025 - Semana: 20/01 al 24/01</option>
                                <option class="text-gray-500 text-sm font-bold mt-2">2025 - Semana: 27/01 al 31/01</option>
                                <option class="text-gray-500 text-sm font-bold mt-2">2025 - Semana: 03/02 al 07/01</option>
                                <option class="text-gray-500 text-sm font-bold mt-2">2025 - Semana: 10/02 al 14/01</option>
                            </select>
                        </label>
                        
                        {{-- Schedule --}}
                        <div class="grid grid-cols-[12%_1fr_1fr_1fr_1fr_1fr] gap-2 p-4 text-center w-full sm:w-[60%]" id="calendar">
                            <div class="text-sm">Hora</div>
                            <div class="text-sm">Lun</div>
                            <div class="text-sm">Mar</div>
                            <div class="text-sm">Mié</div>
                            <div class="text-sm">Jue</div>
                            <div class="text-sm">Vie</div>

                            <!-- Rows and columns -->
                            @foreach ($morningSchedule as $hour)
                                <div class="text-sm p-4 flex justify-center items-center h-[8vh]">{{$hour[0]}} <br> {{$hour[1]}}</div>
                                
                                @foreach ($days as $day)
                                    {{-- First param: hourNumber, Second param: dayNumber--}}
                                    <div wire:click="toggleShowAddComment({{$loop->parent->index}}, {{$loop->index}})" class="border-2 rounded-lg flex justify-center sm:block bg-white cursor-pointer"></div>

                                @endforeach
                            @endforeach

                            <!-- MODAL COMMENT START -->
                            @if ($showComment)
                                <div class="absolute inset-0 z-[10] grid place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 ">
                                    <div class="flex flex-col p-4 w-[80vw] h-[80vh] rounded-lg bg-gray-800 shadow-sm">
                    
                                        <!-- Modal body -->
                                        <div class="flex w-full h-[75%] border-slate-200 py-4 leading-normal text-slate-600 font-light">
                                            <textarea wire:model="comment" class="text-sm text-white w-full h-full bg-gray-900 border-gray-700 rounded-md" placeholder="Añade un comentario..."></textarea>
                                        </div>
                    
                                        <!-- Modal buttons -->
                                        <div class="flex items-center pt-4 justify-end">
                                            <button wire:click="toggleShowAddComment" class="rounded-md bg-gray-800 py-2 px-4 border border-transparent text-center text-sm text-gray-300 transition-all shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                                Cancelar
                                            </button>
                                            <button wire:click="addComment" class="rounded-md bg-white py-2 px-4 border border-transparent text-center text-sm text-black transition-all shadow-md hover:shadow-lg focus:bg-gray-300 focus:shadow-none active:bg-gray-300 hover:bg-gray-300 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                                Guardar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!-- MODAL COMMENT END -->
                        </div>
                    </div>

                    <!-- Modal buttons -->
                    <div class="flex shrink-0 flex-wrap items-center pt-4 justify-end">
                        <button wire:click="toggleShowAddAbsence" class="rounded-md border border-transparent py-2 px-4 text-center text-sm transition-all text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-700 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                            Cancelar
                        </button>
                        <button class="rounded-md bg-white py-2 px-4 border border-transparent text-center text-sm text-black transition-all shadow-md hover:shadow-lg focus:bg-gray-300 focus:shadow-none active:bg-gray-300 hover:bg-gray-300 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                            Enviar
                        </button>
                    </div>   
                </div>
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
                            <button wire:click="orderByDesc" class="rounded-md border border-transparent py-2 px-4 text-center text-sm transition-all text-gray-300 shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                Ordenar descendente
                            </button>
                        @endif

                        @if ($orderAsc)
                            <button wire:click="orderByAsc" class="rounded-md border border-transparent py-2 px-4 text-center text-sm transition-all text-gray-300 shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                                Ordenar ascendente
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
                                            <button wire:click="toggleShowEditAbsence" class="p-1 text-gray-500 transition-colors rounded hover:bg-gray-100 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="Filled" viewBox="0 0 24 24" width="16" height="16" fill="#000"><path d="M1.172,19.119A4,4,0,0,0,0,21.947V24H2.053a4,4,0,0,0,2.828-1.172L18.224,9.485,14.515,5.776Z"/><path d="M23.145.855a2.622,2.622,0,0,0-3.71,0L15.929,4.362l3.709,3.709,3.507-3.506A2.622,2.622,0,0,0,23.145.855Z"/></svg>
                                            </button>
                                            
                                            {{-- Delete button --}}
                                            <button class="p-1 text-gray-500 transition-colors rounded hover:bg-gray-100 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" width="16" height="16" viewBox="0 0 24 24" fill="#000"><path d="m22,4h-4.101c-.465-2.279-2.484-4-4.899-4h-2c-2.414,0-4.434,1.721-4.899,4H2c-.552,0-1,.447-1,1s.448,1,1,1h.86l1.296,13.479c.248,2.578,2.388,4.521,4.977,4.521h5.727c2.593,0,4.733-1.947,4.978-4.528l1.276-13.472h.885c.552,0,1-.447,1-1s-.448-1-1-1Zm-11-2h2c1.302,0,2.402.839,2.816,2h-7.631c.414-1.161,1.514-2,2.816-2Zm4.707,14.293c.391.391.391,1.023,0,1.414-.195.195-.451.293-.707.293s-.512-.098-.707-.293l-2.293-2.293-2.293,2.293c-.195.195-.451.293-.707.293s-.512-.098-.707-.293c-.391-.391-.391-1.023,0-1.414l2.293-2.293-2.293-2.293c-.391-.391-.391-1.023,0-1.414s1.023-.391,1.414,0l2.293,2.293,2.293-2.293c.391-.391,1.023-.391,1.414,0s.391,1.023,0,1.414l-2.293,2.293,2.293,2.293Z"/></svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                {{-- Comment --}}
                                <p class="mt-4 text-gray-600 max-w-[90vw] line-clamp-3">{{$absencesForDayAndHour[$loop->index]->comment}}</p>
                                <a class="text-sm text-gray-600 font-semibold cursor-pointer">Leer más</a> 
                                
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
                    <button wire:click="toggleShowAllAbsences" class="rounded-md bg-gray-800 py-2 px-4 border border-transparent text-center text-sm text-gray-300 transition-all shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                        Cerrar
                    </button>
                </div>
            </div>
        @endif
        <!-- MODAL VIEW END -->


        <!-- MODAL EDIT START -->
        @if ($viewEditAbsence)
            <div class="absolute inset-0 z-[20] grid h-screen w-full place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300">
                <div class="relative m-4 p-4 w-[90vw] h-[90vh] rounded-lg bg-white shadow-sm">

                    <!-- Create the editor container -->
                    <div id="edit" class="max-h-[58vh] sm:!text-lg">
                        <textarea class="p-2 mt-4 text-gray-600 w-full h-full"></textarea>
                    </div>

                    <!-- Modal buttons -->
                    <div class="flex shrink-0 flex-wrap items-center pt-4 justify-end">
                        <button wire:click="toggleShowEditAbsence" class="rounded-md border border-transparent py-2 px-4 text-center text-sm transition-all text-slate-600 hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                            Cancel
                        </button>

                        <button class="rounded-md bg-gray-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 hover:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                            Edit
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <!-- MODAL EDIT END -->
        
    </div>
</div>