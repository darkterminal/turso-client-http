<?php

namespace Darkterminal\TursoHttp\core\Enums;

enum Extension: string
{
    case VECTOR = 'vector';
    case CRYPTO = 'crypto';
    case FUZZY = 'fuzzy';
    case MATH = 'math';
    case STATS = 'stats';
    case TEXT = 'text';
    case UNICODE = 'unicode';
    case UUID = 'uuid';
    case REGEXP = 'regexp';
    case VEC = 'vec';
    case ALL = 'all';
}
