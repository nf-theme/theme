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

class MakeTaxonomyCommand extends Command
{
    protected function configure()
    {
        $this->setName('make:taxonomy')
            ->setDescription('Create a taxonomy')
            ->setHelp('php command make:taxonomy {name} {post_type}')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('post_type', InputArgument::REQUIRED, 'Post Type')
            ->addOption('override', null, InputOption::VALUE_OPTIONAL, 'Do you want to override existing file?', false);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $postType = $input->getArgument('post_type');
        $path = '/app' . DIRECTORY_SEPARATOR . 'Taxonomies';
        $fileName = studly_case($name . 'Taxonomy');
        $fileExtension = '.php';
        $filePath = $path . DIRECTORY_SEPARATOR . $fileName . $fileExtension;
        $typeName = str_slug($name);
        $singleName = title_case(str_singular($name));
        $pluralName = title_case(str_plural($name));

        $taxonomyBlade = <<<'EOT'
namespace App\Taxonomies;

use MSC\Tax;

class {{ $fileName }} extends Tax
{
    public function __construct()
    {
        $config = [
            'slug'   => '{{ $typeName }}',
            'single' => '{{ $singleName }}',
            'plural' => '{{ $pluralName }}'
        ];

        $postType = '{{ $postType }}';

        $args = [];

        parent::__construct($config, $postType, $args);
    }
}
EOT;
        $compiled = BladeCompiler::compileString(
            $taxonomyBlade,
            [
                'fileName' => $fileName,
                'postType' => $postType,
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

        BindingGenerator::run('/app/Providers/TaxonomyServiceProvider.php', '\App\Taxonomies', $fileName);

    }
}
