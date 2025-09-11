<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow all users to submit assessment results
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'petType' => 'required|string|in:dog,cat',
            'preferences' => 'required|array',
            'preferences.size' => 'nullable|string|in:small,medium,large',
            'preferences.hairLength' => 'nullable|string|in:short,long',
            'preferences.personality' => 'nullable|array',
            'recommendedBreeds' => 'required|array|min:1',
            'recommendedBreeds.*.id' => 'required|exists:pets,id',
            'personalityScores' => 'nullable|array',
            'personalityScores.extraversion' => 'nullable|numeric|min:1|max:7',
            'personalityScores.agreeableness' => 'nullable|numeric|min:1|max:7',
            'personalityScores.conscientiousness' => 'nullable|numeric|min:1|max:7',
            'personalityScores.emotionality' => 'nullable|numeric|min:1|max:7',
            'personalityScores.openness' => 'nullable|numeric|min:1|max:7',
            'personalityScores.honesty' => 'nullable|numeric|min:1|max:7',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'petType.required' => 'Please select either dog or cat.',
            'petType.in' => 'Pet type must be either dog or cat.',
            'preferences.required' => 'Preferences are required.',
            'preferences.size.in' => 'Size must be small, medium, or large.',
            'preferences.hairLength.in' => 'Hair length must be short or long.',
            'recommendedBreeds.required' => 'At least one recommended breed is required.',
            'recommendedBreeds.min' => 'At least one recommended breed is required.',
            'recommendedBreeds.*.id.exists' => 'One or more recommended breeds do not exist in our database.',
        ];
    }
}