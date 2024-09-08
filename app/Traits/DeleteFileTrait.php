<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait DeleteFileTrait
{
    protected function deleteFile($existing_file_path)
    {
        if (File::exists($existing_file_path)) {
            return File::delete($existing_file_path);
        }
    }
}
