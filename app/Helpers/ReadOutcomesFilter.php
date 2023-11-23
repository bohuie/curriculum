<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ReadOutcomesFilter implements IReadFilter
{
    private $startRow = 0;

    private $endRow = 0;

    private $columns = [];

    public function __construct($startRow, $endRow, $columns)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
        $this->columns = $columns;
    }

    public function readCell($columnAddress, $row, $worksheetName = '')
    {
        if ($row >= $this->startRow && $row <= $this->endRow) {
            if (in_array($columnAddress, $this->columns)) {
                return true;
            }
        }

        return false;
    }
}
