<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Url;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShortenUrlSuccessfully()
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'https://www.rfc-editor.org/rfc/rfc1738'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['shortened_url']);

        $this->assertDatabaseHas('urls', [
            'original_url' => 'https://www.rfc-editor.org/rfc/rfc1738'
        ]);
    }

    public function testShortenUrlValidationError()
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'invalid-url'
        ]);

        $response->assertStatus(422);
    }

     public function testRedirectToOriginalUrl()
     {
         $url = Url::factory()->create([
             'original_url' => 'https://www.example.com',
             'shortened_url' => 'short1234'
         ]);
 
         $response = $this->get('/short1234');
 
         $response->assertStatus(302)
                  ->assertRedirect('https://www.example.com');
     }
}
