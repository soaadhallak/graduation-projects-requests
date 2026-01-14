<?php

namespace App\Enums;

enum TeamInvitationStatus:string
{
    case PENDING='pending';
    case ACCEPTED='accepted';
    case REVOKED='revoked';
    case EXPIRED='expired';
}
