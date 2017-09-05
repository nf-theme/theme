<?php

namespace NF\Generator;

use Exception;
use NF\Facades\Storage;
use NF\Generator\Generator;

class BindingGenerator extends Generator
{
    public function run($provider_path, $namespace, $classname)
    {
        if (!Storage::has($provider_path)) {
            throw new Exception('provider file not found', 1);
        }

        $full_class_name = $namespace . '\\' . $classname . '::class';

        if (strpos(Storage::read($provider_path), $full_class_name) !== false) {
            throw new Exception('classname is found in provider', 1);
        }

        $stream        = Storage::readStream($provider_path);
        $output_stream = tmpfile();
        $is_started    = false;
        while ($line = fgets($stream)) {
            if (preg_match('/public \$listen/', $line)) {
                $is_started = true;
            }
            if (preg_match('/\];/', $line) && $is_started) {
                fwrite($output_stream, "\t\t${full_class_name},");
                fwrite($output_stream, "\n");
            }
            fwrite($output_stream, $line);
        }

        Storage::updateStream($provider_path, $output_stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

        if (is_resource($output_stream)) {
            fclose($output_stream);
        }
    }

    public function remove($provider_path, $namespace, $classname)
    {
        if (!Storage::has($provider_path)) {
            throw new Exception('provider file not found', 1);
        }

        $full_class_name = $namespace . '\\' . $classname . '::class';

        if (strpos(Storage::read($provider_path), $full_class_name) === false) {
            throw new Exception('classname is not found in provider', 1);
        }

        $output_stream = str_replace("\n\t\t{$full_class_name},", '', Storage::read($provider_path));

        if (!Storage::update($provider_path, $output_stream)) {
            throw new Exception("Could not update provider", 1);
        }
    }
}
