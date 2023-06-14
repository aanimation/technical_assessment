<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use App\Http\Traits\CommonTrait;

use App\Models\VerificationLog;
use App\Exceptions\GeneralApiException;

class VerifyController extends Controller
{
    use CommonTrait;

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::validate($request->all(), [
            'file_content' => [
                'required', 
                File::types(['json'])->max(2048),
            ],
        ]);

        $input = $request->file_content;
        $content = file_get_contents($input);
        $details = json_decode($content);
        
        $name = $this->verifyContent($details);

        $this->throwException('verified', 'success', $name);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
