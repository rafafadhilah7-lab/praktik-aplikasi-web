<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman utama me-redirect ke katalog alat laboratorium.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/alat');

        $responseCatalog = $this->get('/alat');
        $responseCatalog->assertStatus(200);
    }
}
