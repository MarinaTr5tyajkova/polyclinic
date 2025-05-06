<?php

namespace Controller;

use Model\Record;
use Src\View;

class RecordController
{
    public function record(): string
    {
        $records = Record::getAllWithNames();
        return (new View())->render('site.record', ['records' => $records]);
    }
}