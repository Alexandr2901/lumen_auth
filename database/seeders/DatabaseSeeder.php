<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::factory()->count(10)->create();
        Category::factory()->count(10)->create();
        User::factory()->count(10)->create();
        User::factory()->create(['email' => 'admin', 'password' => 'admin']);
        News::factory()->count(1000)->create();
        User::factory()->count(10)->create();

        // $this->call('UsersTableSeeder');
    }
}
