<?php

namespace App\Exports;

use App\Models\RangeOffice;
use Maatwebsite\Excel\Concerns\FromCollection;

class RangeOfficesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RangeOffice::all();
    }
}
