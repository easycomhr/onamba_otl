<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;
    private User $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $this->employee = User::factory()->create([
            'role'        => User::ROLE_EMPLOYEE,
            'name'        => 'Nguyen Van A',
            'employee_code' => 'EMP001',
            'department'  => 'IT',
            'position'    => 'Developer',
            'annual_leave_balance' => 12,
        ]);
    }

    // ── Happy Path ─────────────────────────────────────────────────────────

    public function test_manager_can_list_employees(): void
    {
        $response = $this->actingAs($this->manager)
            ->get(route('admin.employees.index'));

        $response->assertOk()
            ->assertViewHas('employees');
    }

    public function test_search_only_returns_matching_employees(): void
    {
        User::factory()->create(['role' => User::ROLE_EMPLOYEE, 'name' => 'Tran Thi B', 'employee_code' => 'EMP002']);

        $response = $this->actingAs($this->manager)
            ->get(route('admin.employees.index', ['q' => 'Nguyen']));

        $response->assertOk();
        $employees = $response->viewData('employees');
        $this->assertCount(1, $employees);
        $this->assertEquals('EMP001', $employees->first()->employee_code);
    }

    public function test_search_does_not_return_non_employees(): void
    {
        // Create a manager whose employee_code matches the search term
        User::factory()->create([
            'role'          => User::ROLE_MANAGER,
            'employee_code' => 'MGR001',
            'name'          => 'Quan Ly',
        ]);

        $response = $this->actingAs($this->manager)
            ->get(route('admin.employees.index', ['q' => 'MGR001']));

        $response->assertOk();
        $employees = $response->viewData('employees');
        $this->assertCount(0, $employees);
    }

    public function test_manager_can_view_edit_page(): void
    {
        $response = $this->actingAs($this->manager)
            ->get(route('admin.employees.edit', $this->employee->id));

        $response->assertOk()
            ->assertViewHas('employee', fn ($e) => $e->id === $this->employee->id);
    }

    public function test_manager_can_update_employee(): void
    {
        $response = $this->actingAs($this->manager)
            ->put(route('admin.employees.update', $this->employee->id), [
                'name'                 => 'Nguyen Van B',
                'email'                => 'new@example.com',
                'department'           => 'HR',
                'position'             => 'Manager',
                'annual_leave_balance' => 15,
            ]);

        $response->assertRedirect(route('admin.employees.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id'         => $this->employee->id,
            'name'       => 'Nguyen Van B',
            'department' => 'HR',
        ]);
    }

    public function test_manager_can_change_password(): void
    {
        $response = $this->actingAs($this->manager)
            ->post(route('admin.employees.change-password', $this->employee->id), [
                'password'              => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->employee->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->employee->password));
    }

    // ── Validation Errors ──────────────────────────────────────────────────

    public function test_update_fails_with_invalid_data(): void
    {
        $response = $this->actingAs($this->manager)
            ->put(route('admin.employees.update', $this->employee->id), [
                'name'       => '',   // required
                'department' => '',   // required
                'position'   => '',   // required
                'annual_leave_balance' => 999, // max:365
            ]);

        $response->assertSessionHasErrors(['name', 'department', 'position', 'annual_leave_balance']);
    }

    public function test_change_password_fails_when_too_short(): void
    {
        $response = $this->actingAs($this->manager)
            ->post(route('admin.employees.change-password', $this->employee->id), [
                'password'              => 'short',
                'password_confirmation' => 'short',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_change_password_fails_when_not_confirmed(): void
    {
        $response = $this->actingAs($this->manager)
            ->post(route('admin.employees.change-password', $this->employee->id), [
                'password'              => 'newpassword123',
                'password_confirmation' => 'differentpassword',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    // ── Not Found ──────────────────────────────────────────────────────────

    public function test_edit_returns_404_for_missing_employee(): void
    {
        $response = $this->actingAs($this->manager)
            ->get(route('admin.employees.edit', 99999));

        $response->assertNotFound();
    }

    public function test_update_returns_404_for_missing_employee(): void
    {
        $response = $this->actingAs($this->manager)
            ->put(route('admin.employees.update', 99999), [
                'name'                 => 'Test',
                'department'           => 'IT',
                'position'             => 'Dev',
                'annual_leave_balance' => 10,
            ]);

        $response->assertNotFound();
    }

    // ── Authorization ──────────────────────────────────────────────────────

    public function test_employee_cannot_access_employee_list(): void
    {
        $response = $this->actingAs($this->employee)
            ->get(route('admin.employees.index'));

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('admin.employees.index'));

        $response->assertRedirect(route('login'));
    }
}