<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'organization_id' => 'required|exists:organizations,id',
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'timezone' => 'required|string|max:100',
            'venue_name' => 'required|string|max:255',
            'full_address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'group_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'host_name' => 'required|string|max:255',
            'host_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'nullable|numeric|min:0',
            'is_online' => 'required|boolean',
            'event_photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
      public function messages(): array
{
    return [
        'category_id.required'        => 'Category is required.',
        'category_id.exists'          => 'Selected category is invalid.',

        'organization_id.required'    => 'Organization is required.',
        'organization_id.exists'      => 'Selected organization is invalid.',

        'title.required'              => 'Title is required.',
        'title.string'                => 'Title must be a string.',
        'title.max'                   => 'Title may not be greater than 255 characters.',

        'start_time.required'         => 'Start time is required.',
        'start_time.date'             => 'Start time must be a valid date.',

        'end_time.required'           => 'End time is required.',
        'end_time.date'               => 'End time must be a valid date.',
        'end_time.after_or_equal'     => 'End time must be after or equal to start time.',

        'timezone.required'           => 'Timezone is required.',
        'timezone.string'             => 'Timezone must be a string.',
        'timezone.max'                => 'Timezone may not be greater than 100 characters.',

        'venue_name.required'         => 'Venue name is required.',
        'venue_name.string'           => 'Venue name must be a string.',
        'venue_name.max'              => 'Venue name may not be greater than 255 characters.',

        'full_address.string'         => 'Full address must be a string.',

        'latitude.numeric'            => 'Latitude must be a valid number.',
        'longitude.numeric'           => 'Longitude must be a valid number.',

        'image.image'                 => 'Image must be an image file.',
        'image.mimes'                 => 'Image must be a file of type: jpg, jpeg, png, webp.',
        'image.max'                   => 'Image size must not exceed 2MB.',

        'group_image.image'           => 'Group image must be an image file.',
        'group_image.mimes'           => 'Group image must be a file of type: jpg, jpeg, png, webp.',
        'group_image.max'             => 'Group image size must not exceed 2MB.',

        'description.string'          => 'Description must be a string.',

        'host_name.required'          => 'Host name is required.',
        'host_name.string'            => 'Host name must be a string.',
        'host_name.max'               => 'Host name may not be greater than 255 characters.',

        'host_image.image'            => 'Host image must be an image file.',
        'host_image.mimes'            => 'Host image must be a file of type: jpg, jpeg, png, webp.',
        'host_image.max'              => 'Host image size must not exceed 2MB.',

        'price.numeric'               => 'Price must be a valid number.',
        'price.min'                   => 'Price must be at least 0.',

        'is_online.required'          => 'Online status is required.',
        'is_online.boolean'           => 'Online status must be true or false.',

        'event_photos.*.image'        => 'Each event photo must be an image.',
        'event_photos.*.mimes'        => 'Each event photo must be a file of type: jpeg, png, jpg, gif, webp.',
        'event_photos.*.max'          => 'Each event photo size must not exceed 2MB.',
    ];
}
}
