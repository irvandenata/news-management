<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface NewsRepositoryInterface
{
    public function create(Request $request);
    public function delete($id);
}
