<?php

namespace Tests\Feature\Admin;

use App\Models\ExamSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_schedule_multiple_active_exams_for_same_candidate(): void
    {
        $admin = User::factory()->admin()->create();
        $candidate = User::factory()->create();

        ExamSchedule::create([
            'user_id' => $candidate->id,
            'exam_date' => now()->addWeek(),
            'status' => ExamSchedule::STATUS_PLANNED,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.exams.store'), [
            'user_id' => $candidate->id,
            'exam_date' => now()->addWeeks(2)->toDateString(),
            'status' => ExamSchedule::STATUS_PLANNED,
            'note' => 'Second planned attempt',
        ]);

        $response->assertSessionHasErrors('user_id');
        $this->assertSame(1, ExamSchedule::count());
    }
}
