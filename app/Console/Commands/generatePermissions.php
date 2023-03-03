<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class generatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:permissions';

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
        $permissions = [
          'post.view',
          'post.edit',
          'post.delete',
          'post.update',

          'role.view',
          'role.edit',
          'role.delete',
          'role.update',

          'user.view',
          'user.edit',
          'user.delete',
          'user.update'
        ];

        foreach ($permissions as $permission) {
          Permission::create([
            'name' => $permission,
            'guard_name' => 'api',
          ]);
        }
    }
}
