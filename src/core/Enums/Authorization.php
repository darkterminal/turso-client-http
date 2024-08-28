<?php

namespace Darkterminal\TursoHttp\core\Enums;

enum Authorization: string
{
    case READ_ONLY = 'read-only';
    case FULL_ACCESS = 'full-access';
}
