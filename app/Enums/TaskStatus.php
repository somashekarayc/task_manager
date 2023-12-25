<?php

namespace App\Enums;

enum TaskStatus: string
{
    case ToDo = 'todo';
    case Inprogress = 'inprogress';
    case Done = 'done';

}
