<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $publishers = [
            'Gramedia Pustaka Utama',
            'Bentang Pustaka',
            'Mizan',
            'Erlangga',
            'Republika Penerbit',
        ];

        foreach($publishers as $publisher) {
            Publisher::create(['name' => $publisher]);
        }
    }
}
