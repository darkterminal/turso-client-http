<?php

namespace Darkterminal\TursoHttp\core\Enums;

enum InvoiceType: string
{
    case ALL = 'all';
    case UPCOMING = 'upcoming';
    case ISSUED = 'issued';
}
