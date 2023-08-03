<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface CRUDRepositoryInterface
{
    public function create(Request $request);
    public function read();
    public function findBySlug($slug);
    public function update(Request $request,$slug);
    public function delete($slug);
}
