<?php

namespace Database\Seeders;

use App\Models\Shelf;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShelfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $shelves = [];
        $rows = ['A', 'B', 'C'];
        $cols = range(1, 5);

        foreach ($rows as $row) {
            foreach ($cols as $col) {
                $shelves[] = [
                    'code' => $row . '-' . str_pad($col, 2, '0', STR_PAD_LEFT),
                    'location_name' => 'Rak ' . $row . ' Nomor ' . $col,
                ];
            }
        }

        foreach ($shelves as $shelf) {
            Shelf::create($shelf);
        }
    }
}
