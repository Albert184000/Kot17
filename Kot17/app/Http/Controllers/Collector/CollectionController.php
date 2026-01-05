<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    // ទំព័រចុះបញ្ជីប្រមូលលុយប្រចាំថ្ងៃ
    public function daily()
    {
        return view('collector.collections.daily');
    }

    // ទំព័រប្រវត្តិដែលបានប្រមូលរួច
    public function history()
    {
        return view('collector.collections.history');
    }

    // មុខងាររក្សាទុកទិន្នន័យពេលចុច Save
    public function collect(Request $request)
    {
        // កូដសម្រាប់ Save ចូល Database នឹងនៅទីនេះ
        return back()->with('success', 'ការចុះបញ្ជីប្រមូលប្រាក់ត្រូវបានរក្សាទុក!');
    }
}