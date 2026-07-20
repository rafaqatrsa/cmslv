<?php

namespace App\Http\Requests\User;

use App\Services\User\UserContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsernameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        $context = app(UserContext::class);
        $prefix = $context->isParent() ? 'parent' : 'std';

        return [
            'username' => [
                'required',
                'string',
                'max:191',
                "starts_with:{$prefix}",
                Rule::unique('users', 'username')->ignore($this->user()?->id),
            ],
        ];
    }
}
