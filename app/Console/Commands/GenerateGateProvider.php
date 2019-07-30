<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateGateProvider extends Command
{
    const NAME_PROVIDER = '/Providers/GateServiceProvider.php';
    const NAME_STUB = 'stubs/gateProvider.stub';
    const ANCHOR_DEFINE = '{{gate_define}}';

    protected $signature = 'command:generate-gate-provider';
    protected $description = 'Generate Gate Provider';
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $roles = config('role');
        $strGateDefine = '';
        foreach ($roles as $role) {
            $strGateDefine .= $this->addGateDefineString($role);
        }
        $this->createGateProvider($strGateDefine);
    }

    /**
     * @param $strGateDefine
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createGateProvider($strGateDefine)
    {
        $stub = $this->files->get(resource_path(self::NAME_STUB));
        $stub = str_replace(self::ANCHOR_DEFINE, $strGateDefine, $stub);
        $path = app_path('') . self::NAME_PROVIDER;
        $this->files->put($path, $stub);
    }

    public function addGateDefineString($role)
    {
        return "Gate::define('" . $role['id'] . "', 'App\Policies\GatePolicy@checkRole');\r\n\t\t";
    }
}
