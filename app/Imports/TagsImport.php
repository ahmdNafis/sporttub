<?php

namespace App\Imports;

use App\Category;
use App\Tag;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class TagsImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        $category_id = Category::where('category_name', $row['category_name'])->first()->id;
        return new Tag([
            'tag_name' => $row['tag_name'],
            'tag_status' => true,
            'category_id' => $category_id,
        ]);
    }

    public function batchSize(): int {
        return 100;
    }
}
