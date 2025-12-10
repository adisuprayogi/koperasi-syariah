<?php

namespace App\Exports;

use App\Models\TransaksiSimpanan;
use Maatwebsite\Excel\Concerns\FromCollection;

class SimpananTestExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TransaksiSimpanan::all();
    }
}
