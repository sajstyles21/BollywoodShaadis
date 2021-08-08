<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Validator;

class ArticleController extends Controller
{
    
    use ImageUpload;

    public function index()
    {
        return view('add-article-post-form');
    }

    public function store(Request $request)
    {

        $rules = [
            'body' => 'required',
        ];

        $customMessages = [
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with("error", $validator->getMessageBag()->first());
            die();
        }

        $string = $request->body;
        $htmlDom = new \DOMDocument();
        @$htmlDom->loadHTML($string);
        $imageTags = $htmlDom->getElementsByTagName('img');
        $extractedImages = array();
        $i=0;
        foreach($imageTags as $imageTag){
            $altText = $imageTag->getAttribute('alt');
            if($altText==''){
                $i++;
            }
        }

        if($i!=0){
            return redirect()->back()->with('status', 'Alt tag is missing from images.');     
        }

        if (strpos($string, '<p></p>') !== false) {
            return redirect()->back()->with('status', 'Please remove empty <p> tags');     
        }

        Article::create(['body'=>$request->body]);
        return redirect()->back()->with('status', 'Article inserted successfully');
    }

    public function uploadImage(Request $request)
    {
        $rules = [
            'upload' => 'required|image|mimes:jpeg,png|max:2048|dimensions:min_width=700',
        ];

        $customMessages = [
            'upload.dimensions' => 'Image should be atleast 700px in width',
            'upload.image' => 'Image should be either jpeg or png',
            'upload.mimes' => 'Image should be either jpeg or png',
            'upload.max' => 'No Image should be larger than 2 MB in size',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            $msg = $validator->getMessageBag()->first();
            $re = "<script>window.parent.CKEDITOR.tools.callFunction(1, '', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
            die();
        }

        if ($request->hasFile('upload')) {
            $url = $this->UserImageUpload($request->upload);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
            die();
        }
    }
}
