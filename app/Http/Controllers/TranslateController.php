<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateController extends Controller
{
    public function translate(Request $request)
    {
        $text = $request->input('text');
        $target = $request->input('target');
        $source = $request->input('source', 'my');
        if ($target === 'my') {
            // Return original text if Myanmar requested
            $translated = $text;
        } else {
            $tr = new GoogleTranslate();
            $tr->setSource($source);
            $tr->setTarget($target);
            $translated = $tr->translate($text);
        }

        return response()->json(['translated' => $translated]);
    }
}
