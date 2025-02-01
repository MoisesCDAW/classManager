
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- START CSV COMPONENT -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">                
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">CSV</h2>
                
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Te permite cargar los datos de varios profesores a la vez
                        </p>
                    </header>
                
                    <form class="mt-6 space-y-6" enctype="multipart/form-data">
                        <div>
                            <input type="file" class="text-sm text-gray-400" wire:model="CSVFile">
                            @error('CSVFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <button 
                            wire:click.prevent="uploadCSV" wire:loading.attr="disabled" wire:target="CSVFile"
                            class="mt-4 tracking-widest rounded-md bg-gray-300 py-2 px-4 border border-transparent text-center text-xs font-semibold text-black transition-all shadow-md hover:shadow-lg focus:bg-white focus:shadow-none active:bg-white hover:bg-white active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                            SUBIR
                        </button>
                    </form>
                    
                    @if ($successfulUpload)
                        <p class="text-gray-400 text-sm mt-4">Datos subidos.</p>
                    @endif
                </section>
            </div>
        </div>
        <!-- END CSV COMPONENT -->

        <!-- START ADD PROFESSOR COMPONENT -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Agregar profesor</h2>
                
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Incluye a un nuevo profesor en la base de datos
                        </p>
                    </header>
                
                    <form class="mt-6 space-y-6">
                        <div class="flex gap-4 flex-col">
                            <label>
                                <select wire:model="departmentID" class="rounded-md bg-gray-300 py-2 border border-transparent text-sm text-black transition-all shadow-md hover:shadow-lg focus:bg-white focus:shadow-none active:bg-white hover:bg-white active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" wire:model="professorDepartment">
                                    <option>Seleccionar departamento</option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                                @error('departmentID') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </label>

                            <label>
                                <span class="text-sm text-gray-500">Nombre<span> <br>
                                <x-text-input wire:model="professorName" type="text" class="mt-1 block w-full placeholder:text-sm" required placeholder="Ej: Juan Carlos" />
                                @error('professorName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </label>
                            
                            <label>
                                <span class="text-sm text-gray-500">Apellidos<span> <br>
                                <x-text-input wire:model="professorSurnames" type="text" class="mt-1 block w-full placeholder:text-sm" required placeholder="Ej: Fernández Rodríguez"/>
                                @error('professorSurnames') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </label>

                            <label>
                                <span class="text-sm text-gray-500">Email<span> <br>
                                <x-text-input wire:model="email" type="email" class="mt-1 block w-full placeholder:text-sm" required placeholder="Ej: juanfern@gmail.com" />
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </label>
                        </div>

                        <button wire:click="addProfessor" class="mt-4 tracking-widest rounded-md bg-gray-300 py-2 px-4 border border-transparent text-center text-xs font-semibold text-black transition-all shadow-md hover:shadow-lg focus:bg-white focus:shadow-none active:bg-white hover:bg-white active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                            ENVIAR
                        </button>

                        <x-action-message class="me-3" on="profile-updated">
                            {{ __('Saved.') }}
                        </x-action-message>
                    </form>
                </section>
            </div>
        </div>
        <!-- END ADD PROFESSOR COMPONENT -->

    </div>
</div>
