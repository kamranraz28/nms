<?php

namespace App\Exports;

use App\Models\BeatOffice;
use Maatwebsite\Excel\Concerns\FromCollection;

class BeatOfficesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BeatOffice::all();
    }
}
