<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::all();
        return view('admin.news.index', compact('news'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|max:2048',
        ], [
            'title.required' => 'ခေါင်းစဉ်ကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'title.string' => 'ခေါင်းစဉ်သည် စာသားဖြစ်ရမည်။',
            'title.max' => 'ခေါင်းစဉ်သည် ၂၅၅ အက္ခရာထက် မပိုရပါ။',

            'content.required' => 'အကြောင်းအရာကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'content.string' => 'အကြောင်းအရာသည် စာသားဖြစ်ရမည်။',

            'image.required' => 'ပုံကို တင်ရန် လိုအပ်ပါသည်။',
            'image.image' => 'ဖိုင်သည် ပုံပုံစံဖြစ်ရမည်။',
            'image.max' => 'ပုံဖိုင်အရွယ်အစားသည် ၂ မီဂါဘိုင်ထက် မပိုရပါ။',
        ]);


        $imagePath = $request->file('image')->store('news_images', 'public');

        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return response()->json($news, 201);
    }

    public function show(News $news)
    {
        return response()->json($news);
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|max:2048',
        ], [
            'title.required' => 'ခေါင်းစဉ်ကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'title.string' => 'ခေါင်းစဉ်သည် စာသားဖြစ်ရမည်။',
            'title.max' => 'ခေါင်းစဉ်သည် ၂၅၅ အက္ခရာထက် မပိုရပါ။',

            'content.required' => 'အကြောင်းအရာကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'content.string' => 'အကြောင်းအရာသည် စာသားဖြစ်ရမည်။',

            'image.image' => 'ဖိုင်သည် ပုံပုံစံဖြစ်ရမည်။',
            'image.max' => 'ပုံဖိုင်အရွယ်အစားသည် ၂ မီဂါဘိုင်ထက် မပိုရပါ။',
        ]);


        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $news->image = $request->file('image')->store('news_images', 'public');
        }

        $news->update($request->only(['title', 'content', 'image']));

        return response()->json($news);
    }

    public function destroy(News $news)
    {
        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();
        return response()->json(null, 204);
    }
}
