<?php

namespace App\Enums;

enum ProjectStatus:string
{
    case OPEN = 'open';
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case INPROGRESS = 'inprogress';
    case REJECTED = 'rejected';
    case COMPLETE = 'complete';
    case ARCHIVED ='archived';
    case REVOKED='revoked';
}
