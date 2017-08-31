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

class MakeShortCodeCommand extends Command
{
    /**
     * Some configuration for command
     * \Symfony\Component\Console\Command\Command@addArgument($name, $mode = null, $description = '', $default = null)
     * \Symfony\Component\Console\Command\Command@addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
     */
    protected function configure()
    {
        $this->setName('make:shortcode')
            ->setDescription('Create new shortcode')
            ->setHelp('php command make:shortcode {name}')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addOption('override', null, InputOption::VALUE_OPTIONAL, 'Do you want to override existing file?', false);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name           = $input->getArgument('name');
        $path           = '/app' . DIRECTORY_SEPARATOR . 'Shortcodes';
        $file_name      = studly_case($name . 'ShortCode');
        $file_extension = '.php';
        $file_path      = $path . DIRECTORY_SEPARATOR . $file_name . $file_extension;
        $shortcodeName  = str_slug($name, '_');

        $shortcode_blade = <<<'EOT'
namespace App\Shortcodes;

use NF\Abstracts\ShortCode;

class {{$file_name}} extends ShortCode
{
    public $name = '{{$shortcodeName}}';

    public function render($attrs)
    {

    }
}
EOT;

        $compiled = BladeCompiler::compileString($shortcode_blade, ['file_name' => $file_name, 'shortcodeName' => $shortcodeName]);
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

        BindingGenerator::run('/app/Providers/ShortCodeServiceProvider.php', '\App\Shortcodes', $file_name);
    }
}
