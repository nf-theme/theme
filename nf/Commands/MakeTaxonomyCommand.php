<?php

namespace NF\Commands;

use NF\CompileBladeString\Facade\BladeCompiler;
use NF\Facades\Storage;
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

use NF\Abstracts\Taxonomy;

class {{ $fileName }} extends Taxonomy
{
    public $objectType = '{{ $postType }}';

    public $slug = '{{ $typeName }}';

    public $single = '{{ $singleName }}';

    public $plural = '{{ $pluralName }}';

    public $args = [];
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

    }
}
