<?php

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('returns all the tasks when user is administrator', function () {

    // Preparación
    $admin = User::factory()
        ->administrator()
        ->create();

    User::factory()
        ->employee()
        ->has(Task::factory()->count(3))
        ->create();

    User::factory()
        ->employee()
        ->has(Task::factory()->count(2))
        ->create();

    // Acción y Confirmar
    actingAs($admin)
        ->get(route('tasks.index'))
        // La petición sea satisfactoria
        ->assertOk()
        // Nos retorne la vista task-index
        ->assertViewIs('task-index')
        // La vista retornada tenga las 5 tareas registrados
        ->assertViewHas('tasks', Task::all());
});

it('returns only the tasks assigned to the employee user', function () {
    // Preparación
    $employee01 = User::factory()
        ->employee()
        ->has(Task::factory()->count(3))
        ->create();

    $employee02 = User::factory()
        ->employee()
        ->has(Task::factory()->count(3))
        ->create();

    // Acción y Confirmar
    actingAs($employee01)
        ->get(route('tasks.index'))
        // La petición sea satisfactoria
        ->assertOk()
        // Nos retorne la vista task-index
        ->assertViewIs('task-index')
        // La vista retornada solo las 3 tareas registradas
        ->assertViewHas('tasks', $employee01->tasks);
});

it('returns create task view when user is administrator', function () {
    // Preparación
    $admin = User::factory()
        ->administrator()
        ->create();

    // Acción y Confirmar
    actingAs($admin)
        ->get(route('tasks.create'))
        // La petición sea satisfactoria
        ->assertOk()
        // Nos retorne la vista task-create
        ->assertViewIs('task-create');
});

it('returns unauthorized action when user is employee and try to see the create task view', function () {
    // Preparación
    $employee = User::factory()
        ->employee()
        ->create();

    // Acción y Confirmar

    actingAs($employee)
        ->get(route('tasks.create'))
        // La petición está prohibida
        ->assertForbidden();
});

it('can register a task when user is administrator', function () {
    // Preparación
    $admin = User::factory()
        ->administrator()
        ->create();

    $employee = User::factory()
        ->employee()
        ->create();

    $task = [
        'title' => fake()->title,
        'description' => fake()->text(200),
        'user_id' => $employee->id
    ];

    // Acción y Confirmar
    actingAs($admin)
        ->post(route('tasks.store'), $task)
        ->assertRedirect(route('tasks.index'));

    // La tabla "tasks" tiene 1 registro
    assertDatabaseCount('tasks', 1);
    // La tabla "tasks" tiene el registro de la variable task
    assertDatabaseHas('tasks', $task);
});

it('cannot register a task when user is employee', function () {
    // Preparación
    $employee = User::factory()
        ->employee()
        ->create();

    $task = [
        'title' => fake()->title,
        'description' => fake()->text(200),
        'user_id' => $employee->id
    ];

    // Acción y Confirmar
    actingAs($employee)
        ->post(route('tasks.store'), $task)
        // La petición está prohibida
        ->assertForbidden();

    // La tabla tasks no tiene registros
    assertDatabaseCount('tasks', 0);
});

it('will show any task if the user is administrador', function () {
    // Preparación
    $admin = User::factory()
        ->administrator()
        ->create();

    $employee01 = User::factory()
        ->employee()
        ->has(Task::factory()->count(3))
        ->create();

    // Acción y Confirmar
    actingAs($admin)
        ->get(route('tasks.show', $employee01->tasks->first()->id))
        // La petición sea satisfactoria
        ->assertOk()
        // Nos retorne la vista task-show
        ->assertViewIs('task-show')
        // La vista tiene la información del task del empleado 1
        ->assertViewHas('task', $employee01->tasks->first());
});

it('will show only the task related to the user if is an employee', function () {
    // Preparación
    $employee = User::factory()
        ->employee()
        ->has(Task::factory()->count(3))
        ->create();

    // Acción y Confirmar
    actingAs($employee)
        ->get(route('tasks.show', $employee->tasks->first()->id))
        // La petición sea satisfactoria
        ->assertOk()
        // Nos retorne la vista task-show
        ->assertViewIs('task-show')
        // La vista tiene la información del task del empleado logueado
        ->assertViewHas('task', $employee->tasks->first());
});

it('will not show the task related to other employees', function () {
    // Preparación
    $employee01 = User::factory()
        ->employee()
        ->has(Task::factory()->count(3))
        ->create();

    $employee02 = User::factory()
        ->employee()
        ->has(Task::factory()->count(3))
        ->create();

    // Acción y Confirmar
    actingAs($employee01)
        ->get(route('tasks.show', $employee02->tasks->first()->id))
        ->assertForbidden();
});
