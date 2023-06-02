<?php

namespace App\Traits;

use App\Models\File;
use Exception;
use Illuminate\Support\Facades\DB;

trait FileUpdate
{
    public function createFile($data)
    {
        return File::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'size' => $data['size'],
            'type' => $data['type'],
        ]);
    }
}
