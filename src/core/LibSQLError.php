<?php

namespace Darkterminal\TursoHttp\core;

class LibSQLError extends \Exception
{
    public function __construct(string $message, string $code, $exit = true)
    {
        $code = $this->errorText($code);
        $error = php_sapi_name() === 'cli' ? "\n$code \n $message\n\n" : $message;
        if ($exit) {
            echo $error;
            die();
        }
        echo $error;
    }

    private function errorText(string $text): string
    {
        if (php_sapi_name() === 'cli') {
            $redBackgroundWhiteText = "\033[1;41;97m";
            $reset = "\033[0m";
            return $redBackgroundWhiteText . $text . $reset . PHP_EOL;
        } else {
            return $text;
        }
    }
}
