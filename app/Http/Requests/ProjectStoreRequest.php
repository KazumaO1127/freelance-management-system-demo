<?php

namespace App\Http\Requests;

class ProjectStoreRequest extends ProjectRequest
{
    public function rules(): array
    {
        return $this->commonRules();
    }
}

