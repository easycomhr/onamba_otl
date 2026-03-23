<?php

namespace Tests\Unit\Services;

use App\Models\OtRequest;
use App\Models\User;
use App\Services\OtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtServiceTest extends TestCase
{
    use RefreshDatabase;

    private OtService $service;
    private User $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new OtService();
        $this->employee = User::factory()->create([
            'role' => User::ROLE_EMPLOYEE,
            'employee_code' => fake()->unique()->numerify('EMP#####'),
        ]);
    }

    public function test_store_creates_ot_request_with_pending_status(): void
    {
        $otRequest = $this->service->store($this->validData(), $this->employee);

        $this->assertSame(OtRequest::STATUS_PENDING, $otRequest->status);
        $this->assertDatabaseHas('ot_requests', [
            'id' => $otRequest->id,
            'status' => OtRequest::STATUS_PENDING,
        ]);
    }

    public function test_store_returns_ot_request_with_correct_attributes(): void
    {
        $otRequest = $this->service->store([
            'ot_date' => '2026-03-20',
            'hours' => 4.0,
            'reason' => 'Quarter-end deployment',
        ], $this->employee);

        $this->assertInstanceOf(OtRequest::class, $otRequest);
        $this->assertNotNull($otRequest->id);
        $this->assertSame($this->employee->id, $otRequest->user_id);
        $this->assertSame('2026-03-20', $otRequest->ot_date->toDateString());
        $this->assertSame('4.0', $otRequest->hours);
        $this->assertSame('Quarter-end deployment', $otRequest->reason);
        $this->assertSame(OtRequest::STATUS_PENDING, $otRequest->status);
    }

    public function test_store_does_not_set_code_manually(): void
    {
        $otRequest = $this->service->store($this->validData(), $this->employee);

        $this->assertNotEmpty($otRequest->code);
        $this->assertMatchesRegularExpression('/^OT-\d{6}-\d{2}$/', $otRequest->code);
    }

    public function test_store_throws_exception_when_duplicate_same_user_same_date(): void
    {
        $this->makeOtRequest($this->employee, '2026-03-20');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(
            'Duplicate OT: user_id=' . $this->employee->id . ' ot_date=2026-03-20'
        );

        $this->service->store([
            'ot_date' => '2026-03-20',
            'hours' => 3.0,
            'reason' => 'Duplicate attempt',
        ], $this->employee);
    }

    public function test_store_different_user_same_date_is_allowed(): void
    {
        $otherEmployee = User::factory()->create([
            'role' => User::ROLE_EMPLOYEE,
            'employee_code' => fake()->unique()->numerify('EMP#####'),
        ]);

        $first = $this->service->store([
            'ot_date' => '2026-03-21',
            'hours' => 2.0,
            'reason' => 'First employee OT',
        ], $this->employee);

        $second = $this->service->store([
            'ot_date' => '2026-03-21',
            'hours' => 3.5,
            'reason' => 'Second employee OT',
        ], $otherEmployee);

        $this->assertNotSame($first->user_id, $second->user_id);
        $this->assertDatabaseCount('ot_requests', 2);
    }

    public function test_store_same_user_different_date_is_allowed(): void
    {
        $first = $this->service->store([
            'ot_date' => '2026-03-22',
            'hours' => 2.0,
            'reason' => 'Sprint closeout',
        ], $this->employee);

        $second = $this->service->store([
            'ot_date' => '2026-03-23',
            'hours' => 1.5,
            'reason' => 'Production monitoring',
        ], $this->employee);

        $this->assertSame($this->employee->id, $first->user_id);
        $this->assertSame($this->employee->id, $second->user_id);
        $this->assertDatabaseCount('ot_requests', 2);
    }

    private function validData(): array
    {
        return [
            'ot_date' => '2026-03-18',
            'hours' => 2.5,
            'reason' => 'Release support',
        ];
    }

    private function makeOtRequest(User $employee, string $otDate): OtRequest
    {
        return OtRequest::create([
            'user_id' => $employee->id,
            'ot_date' => $otDate,
            'hours' => 2.0,
            'reason' => 'Existing OT request',
            'status' => OtRequest::STATUS_PENDING,
        ]);
    }
}
