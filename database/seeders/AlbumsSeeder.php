<?php

namespace Database\Seeders;

use App\Models\Album;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlbumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i = 1;
        $albums = [
            [ 'id'             => $i++,  'name'           => 'Cars',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Nature',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Dark',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Food',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Models',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Music',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Space',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Sport',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Technologies',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Vector',  'user_id' => 1], 
            [ 'id'             => $i++,  'name'           => 'Others',  'user_id' => 1],  
        ];

        Album::insert($albums);
    }
}
