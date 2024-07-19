<?php

namespace Darkterminal\TursoHttp\core\Enums;

enum DataType: string
{
    case NULL = 'null';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case TEXT = 'text';
    case BLOB = 'blob';
}
