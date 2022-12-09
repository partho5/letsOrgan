<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class handleUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rulesForBook=[
            'name' => 'required',
            'author' => 'required',
            'category' => 'required',
            'my_file' => 'required'
        ];

        $rulesForOtherFile=[
            'title' => 'required',
            'author' => 'required',
            'category' => 'required',
            'my_file' => 'required'
        ];

        $identifier=$this->input('identifier');

        if($identifier=='book'){
            return $rulesForBook;
        }
        elseif($identifier=='otherFile'){
            return $rulesForOtherFile;
        }


    }

    public function attributes()
    {
        //un necessary here, because messages() function returns the msg to show
        return [
            'name' => 'Book Name',
            'author' => 'Author name',
            'category' => 'Book Category',
            'my_file' => ''
        ];
    }

    public function messages()
    {
        $msgForBook=[
            'name.required' => 'Book Name/Title can\' be empty',
            'author.required' => 'Author name can\'t be empty',
            'category.required' => 'Please provide a category name',
            'my_file.required' => 'You forgot to choose a file'
        ];

        $msgForOtherFile=[
            'title.required' => 'Title/Book Name can\' be empty',
            'category.required' => 'Please provide a category name',
            'my_file.required' => 'You forgot to choose a file'
        ];

        $identifier=$this->input('identifier');

        if($identifier=='book'){
            return $msgForBook;
        }
        elseif ($identifier=='otherFile'){
            return $msgForOtherFile;
        }
    }
}
