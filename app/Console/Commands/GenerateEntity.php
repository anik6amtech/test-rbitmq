<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateEntity extends Command
{
    protected $signature = 'generate:entity {entity}';

    protected $description = 'Generate model, repository interface, and class for specified entity';

    public function handle()
    {
        $entity = $this->argument('entity');
        // Create directory for models if not exists
        $modelPath = app_path('Models');
        if (! File::isDirectory($modelPath)) {
            File::makeDirectory($modelPath, 0755, true);
        }

        // Create model file if not exists
        $modelPath = "{$modelPath}/{$entity}.php";
        if (! File::exists($modelPath)) {
            $modelContent = "<?php\n\nnamespace App\Models;\n\nclass {$entity} extends BaseModel\n{\n   //Define model properties and relationships here\n}\n";
            File::put($modelPath, $modelContent);
        }

        // Create directory for repository interfaces if not exists
        $interfacePath = app_path('Contracts/Interfaces');
        if (! File::isDirectory($interfacePath)) {
            File::makeDirectory($interfacePath, 0755, true);
        }

        // Create directory for specific entity if not exists
        $entityInterfacePath = app_path('Contracts/Interfaces');
        if (! File::isDirectory($entityInterfacePath)) {
            File::makeDirectory($entityInterfacePath, 0755, true);
        }

        // Create repository interface file if not exists
        $interfacePath = "{$entityInterfacePath}/{$entity}RepositoryInterface.php";
        if (! File::exists($interfacePath)) {
            $interfaceContent = "<?php\n\nnamespace App\Contracts\Interfaces;\n\nuse App\Contracts\BaseInterface;\n\ninterface {$entity}RepositoryInterface extends BaseInterface\n{\n    // Define interface methods here\n}\n";
            File::put($interfacePath, $interfaceContent);
        }

        // Create directory for repository classes if not exists
        $repositoryPath = app_path('Contracts/Repositories');
        if (! File::isDirectory($repositoryPath)) {
            File::makeDirectory($repositoryPath, 0755, true);
        }

        // Create directory for specific entity if not exists
        $entityRepositoryPath = app_path('Contracts/Repositories');
        if (! File::isDirectory($entityRepositoryPath)) {
            File::makeDirectory($entityRepositoryPath, 0755, true);
        }

        // Create repository class file if not exists
        $repositoryPath = "{$entityRepositoryPath}/{$entity}Repository.php";
        if (! File::exists($repositoryPath)) {
            $repositoryContent = "<?php\n\nnamespace App\Contracts\Repositories;\n\nuse App\Contracts\BaseRepository;\nuse App\Contracts\Interfaces\\{$entity}RepositoryInterface;\nuse App\Models\\{$entity};\n\nclass {$entity}Repository extends BaseRepository implements {$entity}RepositoryInterface\n{\n    public function __construct({$entity} \$model)\n    {\n        parent::__construct(\$model);\n    }\n\n    // Implement repository methods here\n}\n";
            File::put($repositoryPath, $repositoryContent);
        }

        // Create migration file
        $migrationName = 'create_'.Str::plural(Str::snake($entity)).'_table';
        $this->call('make:migration', ['name' => $migrationName]);

        $this->info("{$entity} model, repository interface, and class generated successfully.");
    }
}
