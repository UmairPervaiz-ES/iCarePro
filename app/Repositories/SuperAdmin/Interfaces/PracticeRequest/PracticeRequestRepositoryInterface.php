<?php

namespace App\Repositories\SuperAdmin\Interfaces\PracticeRequest;

interface PracticeRequestRepositoryInterface 
{
    public function initialPracticeRequest($request);
    public function initialPracticeRequestResponse($request);
    public function practiceRequestGet($request);
    public function practiceRequestResponse($request);

 
}