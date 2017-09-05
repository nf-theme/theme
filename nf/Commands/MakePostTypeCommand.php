<?php

namespace NF\Commands;

use NF\CompileBladeString\Facade\BladeCompiler;
use NF\Facades\Storage;
use NF\Facades\BindingGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakePostTypeCommand extends Command
{
    protected function configure()
    {
        $this->setName('make:posttype')
            ->setDescription('Create new custom post type')
            ->setHelp('php command make:posttype {name}')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addOption('override', null, InputOption::VALUE_OPTIONAL, 'Do you want to override existing file?', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $path = '/app' . DIRECTORY_SEPARATOR . 'CustomPosts';
        $fileName = studly_case($name . 'CustomType');
        $fileExtension = '.php';
        $filePath = $path . DIRECTORY_SEPARATOR . $fileName . $fileExtension;
        $typeName = str_slug($name);
        $singleName = title_case(str_singular($name));
        $pluralName = title_case(str_plural($name));

        $customTypeBlade = <<<'EOT'
/**
 * This file create {{ $singleName }} custom post type
 *
 */

namespace App\CustomPosts;

use NF\Abstracts\CustomPost;

class {{ $fileName }} extends CustomPost
{
    /**
     * [$type description]
     * @var string
     */
    public $type = '{{ $typeName }}';

    /**
     * [$single description]
     * @var string
     */
    public $single = '{{ $singleName }}';

    /**
     * [$plural description]
     * @var string
     */
    public $plural = '{{ $pluralName }}';

    /**
     * $args optional
     * @var array
     */
    public $args = ['menu_icon' => 'dashicons-location-alt'];

}
EOT;
        $compiled = BladeCompiler::compileString(
            $customTypeBlade,
            [
                'fileName' => $fileName,
                'typeName' => $typeName,
                'singleName' => $singleName,
                'pluralName' => $pluralName,
            ]
        );
        $compiled = <<<EOT
<?php
{$compiled}

EOT;
        if (Storage::has($filePath)) {
            if ($input->getOption('override') !== false) {
                Storage::delete($filePath);
            } else {
                $output->write("<error>File exists: {$filePath}</error>", true);
                return false;
            }
        }

        Storage::write($filePath, $compiled);
        $output->write("<info>{$filePath}</info>", true);

        BindingGenerator::run('/app/Providers/CustomPostServiceProvider.php', '\App\CustomPosts', $fileName);
    }
}
