<?php

namespace MedianetDev\LaravelAuthApi\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class PublishApiUserModel extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apiuser:publish-user-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the ApiUser model to App\Models\ApiUser';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../Models/ApiUser.php';
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        \Artisan::call('vendor:publish', ['--provider' => 'MedianetDev\LaravelAuthApi\LaravelAuthApiServiceProvider', '--tag' => 'config']);

        $this->question('Uncomment  the user_model_fqn in your laravel-auth-api.php config file to set your current active User Model to the one just created.');

        $destination_path = $this->laravel['path'].'/Models/ApiUser.php';

        if ($this->files->exists($destination_path)) {
            $this->error('ApiUser model already exists!');

            return false;
        }

        $this->makeDirectory($destination_path);

        $this->files->put($destination_path, $this->buildClass());

        $this->info($this->laravel->getNamespace().'Models\ApiUser.php created successfully.');
    }

    /**
     * Build the class. Replace MedianetDev namespace with App one.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name = false)
    {
        $stub = $this->files->get($this->getStub());

        return $this->makeReplacements($stub);
    }

    /**
     * Replace the namespace for the given stub.
     * Replace the User model, if it was moved to App\Models\User.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function makeReplacements(&$stub)
    {
        $stub = str_replace('MedianetDev\LaravelAuthApi\Models;', $this->laravel->getNamespace().'Models;', $stub);

        if (! $this->files->exists($this->laravel['path'].'/User.php') && $this->files->exists($this->laravel['path'].'/Models/User.php')) {
            $stub = str_replace($this->laravel->getNamespace().'User', $this->laravel->getNamespace().'Models\User', $stub);
        }

        return $stub;
    }
}
