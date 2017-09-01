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

class MakeWidgetCommand extends Command
{
    protected function configure()
    {
        $this->setName('make:widget')
            ->setDescription('Create new widget')
            ->setHelp('php command make:widget {name}')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('description', InputArgument::OPTIONAL, 'Description')
            ->addOption('override', null, InputOption::VALUE_OPTIONAL, 'Do you want to override existing file?', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $description = $input->getArgument('description');
        $path = '/App' . DIRECTORY_SEPARATOR . 'Widgets';
        $fileName = studly_case($name . 'Widget');
        $fileExtension = '.php';
        $filePath = $path . DIRECTORY_SEPARATOR . $fileName . $fileExtension;
        $widgetSlug = str_slug($name) . '-widget';
        $widgetName = title_case($name);

        $widgetBlade = <<<'EOT'
namespace App\Widgets;

use MSC\Widget;

class {{ $fileName }} extends Widget
{
    public function __construct()
    {
        $widget = [
            'id'          => __('{{ $widgetSlug }}', 'textdomain'),
            'label'       => __('{{ $widgetName }}', 'textdomain'),
            'description' => __('{{ $description }}', 'textdomain'),
        ];

        $fields = [
            [
                'label' => __('Test Field', 'textdomain'),
                'name'  => 'test_field',
                'type'  => 'text',
            ],
        ];

        parent::__construct($widget, $fields);
    }

    public function handle($instance)
    {
        var_dump($instance);
	}
}
EOT;
        $compiled = BladeCompiler::compileString(
            $widgetBlade,
            [
                'fileName' => $fileName,
                'widgetSlug' => $widgetSlug,
                'widgetName' => $widgetName,
                'description' => $description
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

        BindingGenerator::run('/app/Providers/WidgetServiceProvider.php', '\App\Widgets', $fileName);
    }
}
