<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class assignRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $user = \App\Models\User::create([
        "id" => 1,
        "name" => "admin",
        "email" => "admin@example.com",
        "password" => Hash::make("password"),
      ]);
  
      $user->assignRole('admin');
    }
}
