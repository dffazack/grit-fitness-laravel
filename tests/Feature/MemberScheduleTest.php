<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ClassSchedule;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_authenticated_member_can_view_schedule_page(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get('/member/schedule');

        $response->assertStatus(200);
    }

    public function test_member_can_book_a_class(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'membership_status' => 'active',
        ]);
        $schedule = ClassSchedule::factory()->create();

        $response = $this->actingAs($member)->post(route('member.class.book', $schedule));

        $this->assertDatabaseHas('bookings', [
            'user_id' => $member->id,
            'class_schedule_id' => $schedule->id,
        ]);

        $response->assertRedirect();
    }

    public function test_member_can_cancel_a_booking(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $schedule = ClassSchedule::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $member->id,
            'class_schedule_id' => $schedule->id,
        ]);

        $response = $this->actingAs($member)->post(route('member.class.cancel', $schedule));

        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);

        $response->assertRedirect();
    }
}
