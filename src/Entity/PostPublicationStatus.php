<?php

declare(strict_types=1);

namespace App\Entity;

enum PostPublicationStatus: string
{
    case PUBLISHED = 'published';
    case TO_VALIDATE = 'to_validate';
    case DRAFT = 'draft';
}
