<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class generateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:roles';

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
        $roles = [
          'admin',
          'editor',
          'user'
        ];

        foreach($roles as $role) {
          Role::create([
            'name' => $role,
            'guard_name' => 'api'
          ]);
        }
        
    }
}
