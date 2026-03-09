<?php

use App\Livewire\Admin\ProjectForm;
use App\Livewire\Admin\ProjectList;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function adminUser(): User
{
    return User::factory()->create(['email' => 'aclearmonth@gmail.com']);
}

test('guests are redirected to login from project admin routes', function () {
    $this->get('/admin/projects')->assertRedirect('/login');
    $this->get('/admin/projects/create')->assertRedirect('/login');
});

test('authenticated admin can view projects list', function () {
    $this->actingAs(adminUser());

    $this->get('/admin/projects')->assertStatus(200);
});

test('project list component renders projects', function () {
    $this->actingAs(adminUser());

    Project::factory()->count(3)->create();

    Livewire::test(ProjectList::class)
        ->assertStatus(200);
});

test('project list can search by title', function () {
    $this->actingAs(adminUser());

    Project::factory()->create(['title' => 'Unique Search Term']);
    Project::factory()->create(['title' => 'Another Project']);

    Livewire::test(ProjectList::class)
        ->set('search', 'Unique Search Term')
        ->assertSee('Unique Search Term')
        ->assertDontSee('Another Project');
});

test('project list can filter by status', function () {
    $this->actingAs(adminUser());

    Project::factory()->create(['title' => 'Done Project', 'status' => 'completed']);
    Project::factory()->create(['title' => 'WIP Project', 'status' => 'in_progress']);

    Livewire::test(ProjectList::class)
        ->set('status', 'completed')
        ->assertSee('Done Project')
        ->assertDontSee('WIP Project');
});

test('project can be deleted from list', function () {
    $this->actingAs(adminUser());

    $project = Project::factory()->create();

    Livewire::test(ProjectList::class)
        ->call('deleteProject', $project->id);

    expect(Project::find($project->id))->toBeNull();
});

test('project featured can be toggled', function () {
    $this->actingAs(adminUser());

    $project = Project::factory()->create(['featured' => false]);

    Livewire::test(ProjectList::class)
        ->call('toggleFeatured', $project->id);

    expect($project->fresh()->featured)->toBeTrue();
});

test('authenticated admin can visit create project page', function () {
    $this->actingAs(adminUser());

    $this->get('/admin/projects/create')->assertStatus(200);
});

test('project form can create a new project', function () {
    $this->actingAs(adminUser());

    Livewire::test(ProjectForm::class)
        ->set('title', 'My New Project')
        ->set('description', 'A great project description that is long enough.')
        ->set('technologies', ['Laravel', 'Livewire'])
        ->set('status', 'in_progress')
        ->set('order', 1)
        ->call('save')
        ->assertHasNoErrors();

    expect(Project::where('title', 'My New Project')->exists())->toBeTrue();
});

test('project form validates required fields', function () {
    $this->actingAs(adminUser());

    Livewire::test(ProjectForm::class)
        ->set('title', '')
        ->set('description', '')
        ->call('save')
        ->assertHasErrors(['title', 'description']);
});

test('project form can edit an existing project', function () {
    $this->actingAs(adminUser());

    $project = Project::factory()->create(['title' => 'Old Title']);

    Livewire::test(ProjectForm::class, ['projectId' => $project->id])
        ->set('title', 'Updated Title')
        ->set('description', 'Updated description that is long enough for validation.')
        ->call('save')
        ->assertHasNoErrors();

    expect($project->fresh()->title)->toBe('Updated Title');
});

test('authenticated admin can visit edit project page', function () {
    $this->actingAs(adminUser());

    $project = Project::factory()->create();

    $this->get("/admin/projects/{$project->id}/edit")->assertStatus(200);
});
