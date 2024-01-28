<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function collection()
    {
        return User::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'verified_at',
            'created_at',
            'updated_at'
            // Add more headings as needed
        ];
    }*/
    protected $columns = ['id', 'name', 'email']; // Specify the columns to export

    public function collection()
    {
        // Fetch only the specified columns
        return User::select($this->columns)->get();
    }

    public function headings(): array
    {
        // Customize column headings
        return [
            'User ID',
            'Full Name',
            'Email Address',
            // Add more headings as needed
        ];
    }
}
