<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerpustakaanTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_load_successfully(): void
    {
        $this->seed();
        $book = Book::firstOrFail();

        $this->get('/')->assertOk();
        $this->get('/katalog')->assertOk();
        $this->get('/katalog/' . $book->id)->assertOk();
        $this->get('/kegiatan')->assertOk();
        $this->get('/admin/login')->assertOk();
        $this->get('/cron/sync-fines?token=berkelana2026')->assertOk()->assertJson(['ok' => true]);
    }

    public function test_member_dashboard_and_admin_access_control(): void
    {
        $this->seed();

        $member = User::where('email', 'anggota@berkelana.id')->firstOrFail();
        $admin = User::where('email', 'admin@berkelana.id')->firstOrFail();

        $this->actingAs($member)->get('/dashboard')->assertOk();
        $this->actingAs($member)->get('/admin')->assertForbidden();
        $this->actingAs($admin)->get('/admin')->assertOk();
    }
}
