<?php

namespace App\Enums;

enum TaskStatus: string
{
    case ToDo = 'todo';
    case Inprogress = 'in-progress';
    case Done = 'done';

}
