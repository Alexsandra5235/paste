<?php

namespace Database\Seeders;

use App\Models\Paste;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Service\PasteService;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Orchid\Platform\Models\Role;


class DatabaseSeeder extends Seeder
{
    protected PasteService $pasteService;
    public function __construct(PasteService $pasteService){
        $this->pasteService = $pasteService;
    }
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


//        Role::create(['name' => 'user']);
//        Role::create(['name' => 'admin']);

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
