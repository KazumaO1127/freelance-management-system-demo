<?php

namespace App\Http\Requests;

class ProjectUpdateRequest extends ProjectRequest
{
    public function rules(): array
    {
        return $this->commonRules();
    }
}

