<?php

namespace NF\CompileBladeString;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler as Compiler;
use NF\Facades\App;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BladeCompiler
{
    public function compileString($value, array $args = array())
    {
        $filesystem = new Filesystem();
        $compiler   = new Compiler($filesystem, App::config('cache_path'));
        $generated  = $compiler->compileString($value);

        // var_dump($generated); die();

        ob_start() and extract($args, EXTR_SKIP);
        try
        {
            eval('?>' . $generated);
        } catch (\Exception $e) {
            ob_get_clean();throw new BadRequestHttpException("Error while parse content", null, 1);
        }

        $content = ob_get_clean();

        return $content;
    }
}
