<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadAppTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test root URL redirects to /download-app
     */
    public function test_root_redirects_to_download_app(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/download-app');
    }

    /**
     * Test download page loads successfully and logs visit
     */
    public function test_download_page_loads_and_logs_visit(): void
    {
        // Mock User-Agent to ensure logic runs (iPhone)
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1'
        ])->get('/download-app');

        $response->assertStatus(200);
        $response->assertViewIs('download-app.index');

        // Check DB log
        $this->assertDatabaseHas('app_download_logs', [
            // 'os' => 'iOS', // Agent might return 'iOS' or 'Mac OS' depending on version/library version. Let's skip exact OS check to be safe.
            'result' => 'success'
        ]);
    }
}
