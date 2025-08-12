<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SendMail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    public function store(Request $request)
    {
        $superUserEmails = User::where('super_user', 1)->pluck('email')->toArray();
        $request->validate([
            'from' => 'required|email',
            'department' => 'required|string',
            'phone' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
             'file' => 'nullable|file|mimes:pdf,jpg,png,doc,docx,xlsx,xls,csv'
        ], [
            'department.required' => 'ဌာနကို ဖြည့်ရန်လိုအပ်သည်။',
            'phone.required' => 'ဖုန်းနံပါတ်ကို ဖြည့်ရန်လိုအပ်သည်။',
            'title.required' => 'ခေါင်းစဉ်ကို ဖြည့်ရန်လိုအပ်သည်။',
            'body.required' => 'စာအကြောင်းကို ဖြည့်ရန်လိုအပ်သည်။',
            'file.mimes' => 'ဖိုင်အမျိုးအစားသည် PDF, JPG, PNG, DOC, DOCX, XLSX, XLS, CSV ဖြစ်ရမည်။',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads', 'public');
        }

        // Save to DB using Model
        $sendMail = SendMail::create([
            'from' => $request->from,
            'to' => $superUserEmails[0] ?? null,
            'department' => $request->department,
            'phone' => $request->phone,
            'title' => $request->title,
            'body' => $request->body,
            'file' => $filePath
        ]);

        // Send email
        Mail::html("
    <p><strong>Department:</strong> {$sendMail->department}</p>
    <p><strong>Phone:</strong> {$sendMail->phone}</p>
    <p>{$sendMail->body}</p>
", function ($message) use ($sendMail) {
            $message->from($sendMail->from, $sendMail->department)
                ->to($sendMail->to)
                ->subject($sendMail->title);

            if ($sendMail->file) {
                $message->attach(storage_path('app/public/' . $sendMail->file));
            }
        });


        return back()->with('success', 'Mail sent successfully!');
    }

    public function sendBack(Request $request)
    {
        $superUserEmails = User::where('super_user', 1)->pluck('email')->toArray();

        $request->validate([
            'department' => 'required|string',
            'phone' => 'nullable|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,jpg,png,doc,docx,xlsx,xls,csv'
        ], [
            'department.required' => 'ဌာနကို ဖြည့်ရန်လိုအပ်သည်။',
            'phone.required' => 'ဖုန်းနံပါတ်ကို ဖြည့်ရန်လိုအပ်သည်။',
            'title.required' => 'ခေါင်းစဉ်ကို ဖြည့်ရန်လိုအပ်သည်။',
            'body.required' => 'စာအကြောင်းကို ဖြည့်ရန်လိုအပ်သည်။',
            'file.mimes' => 'ဖိုင်အမျိုးအစားသည် PDF, JPG, PNG, DOC, DOCX, XLSX, XLS, CSV ဖြစ်ရမည်။',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads', 'public');
        }

        // Save to DB, fix from and to
        $sendMail = SendMail::create([
            'from' =>implode(',', $superUserEmails),               // Sender email
            'to' => $request->to, // Store super user emails as CSV string or handle differently
            'department' => $request->department,
            'phone' => $request->phone ?? '',        // handle missing phone
            'title' => $request->title,
            'body' => $request->body,
            'file' => $filePath
        ]);

        // Send mail to super users
        Mail::html("
        <p><strong>Department:</strong> {$sendMail->department}</p>
        <p><strong>Phone:</strong> {$sendMail->phone}</p>
        <p>{$sendMail->body}</p>
    ", function ($message) use ($sendMail, $superUserEmails) {
            $message->from($sendMail->from, $sendMail->department)
                ->to($sendMail->to)  // array of emails
                ->subject($sendMail->title);

            if ($sendMail->file) {
                $message->attach(storage_path('app/public/' . $sendMail->file));
            }
        });

        return back()->with('success', 'Mail sent successfully!');
    }


    public function create()
    {
        return view('sendmail.create');
    }

    public function sendMailList()
    {
        $user = auth()->user();
        if ($user && $user->super_user) {
            $mails = SendMail::where('to', $user->email)->get();
        } else {
            $mails = collect();
        }
        return view('admin.sendmail.list', compact('mails'));
    }
}
