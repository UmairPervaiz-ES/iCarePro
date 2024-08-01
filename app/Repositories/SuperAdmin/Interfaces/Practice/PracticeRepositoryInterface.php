<?php

namespace App\Repositories\SuperAdmin\Interfaces\Practice;

interface PracticeRepositoryInterface
{
    public function practices($request);
    public function practiceDetails($id);
}
