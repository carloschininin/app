<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security;

enum Permission: string
{
    case MASTER = 'master';

    case NEW = 'new';
    case LIST = 'list';
    case SHOW = 'show';
    case EDIT = 'edit';
    case PRINT = 'print';
    case REPORT = 'report';
    case EXPORT = 'export';
    case IMPORT = 'import';
    case ENABLE = 'enable';
    case DISABLE = 'disable';
    case DELETE = 'delete';

    case LIST_ALL = 'list_all';
    case SHOW_ALL = 'show_all';
    case EDIT_ALL = 'edit_all';
    case PRINT_ALL = 'print_all';
    case REPORT_ALL = 'report_all';
    case EXPORT_ALL = 'export_all';
    case IMPORT_ALL = 'import_all';
    case ENABLE_ALL = 'enable_all';
    case DISABLE_ALL = 'disable_all';
    case DELETE_ALL = 'delete_all';
}
