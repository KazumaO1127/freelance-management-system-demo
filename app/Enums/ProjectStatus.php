<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Contact = 'contact';
    case Negotiation = 'negotiation';
    case Contracted = 'contracted';
    case Working = 'working';
    case Completed = 'completed';
}
