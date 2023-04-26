<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Tarea') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900">
                        Datos de la Tarea
                    </h2>

                    <div class="mt-6">
                        <x-input-label value="ID" />
                        <p>{{ $task->id }}</p>
                    </div>

                    <div class="mt-4">
                        <x-input-label value="Título" />
                        <p>{{ $task->title }}</p>
                    </div>

                    <div class="mt-4">
                        <x-input-label value="Descripción" />
                        <p>{{ $task->description }}</p>
                    </div>

                    <div class="mt-4">
                        <x-input-label value="Empleado" />
                        <p>{{ $task->user->name }}</p>
                    </div>

                    <form action="{{ route('tasks.index') }}" method="GET" class="mt-4">
                        <x-primary-button>Regresar</x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
