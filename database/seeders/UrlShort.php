<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Url;

class UrlShort extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Url::factory()->create([
            'original_url' => 'https://www.rfc-editor.org/rfc/rfc1738',
            'shortened_url' => 'hLnx9ha2',
        ]);
    }
}
