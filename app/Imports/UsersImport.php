<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($row["nomor"] === null || $row["item"] === null || $row["tanggal"] === null) {
            return null;
        }
        return new Transaction([
            'date_transactions'     => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["tanggal"]),
            'no_transactions'       => $row["nomor"],
            'item_transactions'     => $row["item"]
        ]);
    }
}
