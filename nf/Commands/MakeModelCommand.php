<?php

namespace NF\Commands;

use NF\CompileBladeString\Facade\BladeCompiler;
use NF\Facades\BindingGenerator;
use NF\Facades\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModelCommand extends Command
{
    /**
     * Some configuration for command
     * \Symfony\Component\Console\Command\Command@addArgument($name, $mode = null, $description = '', $default = null)
     * \Symfony\Component\Console\Command\Command@addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
     */
    protected function configure()
    {
        $this->setName('make:model')
            ->setDescription('Create new model')
            ->setHelp('php command make:model {name}')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addOption('override', null, InputOption::VALUE_OPTIONAL, 'Do you want to override existing file?', false);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name           = $input->getArgument('name');
        $path           = '/app' . DIRECTORY_SEPARATOR . 'Models';
        $file_name      = studly_case($name);
        $file_extension = '.php';
        $file_path      = $path . DIRECTORY_SEPARATOR . $file_name . $file_extension;

        $model_blade = <<<'EOT'
namespace App\Models;

use NF\Models\Model;

class {{$file_name}} extends Model
{
    // protected $table = '';
    protected $fillable = [];
}

EOT;

        $compiled = BladeCompiler::compileString($model_blade, ['file_name' => $file_name]);
        $compiled = <<<EOT
<?php

{$compiled}

EOT;
        if (Storage::has($file_path)) {
            if ($input->getOption('override') !== false) {
                Storage::delete($file_path);
            } else {
                $output->write("<error>File exists: {$file_path}</error>", true);
                return false;
            }
        }

        Storage::write($file_path, $compiled);
        $output->write("<info>{$file_path}</info>", true);

    }
}
