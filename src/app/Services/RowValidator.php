<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ExcelParseRequest;

class RowValidator
{
    public function validate(array $data): array
    {
        $validator = Validator::make($data, (new ExcelParseRequest())->rules(), (new ExcelParseRequest())->messages());

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        return [];
    }
}