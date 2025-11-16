<?php

namespace App\Http\Controllers;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Department;
class HomeController extends Controller
{
    public function index()
    {
         $categories = Category::where('status', 1)->latest()->get();
         // Lấy danh sách chuyên khoa (hoạt động)
        $departments = Department::where('status', 'active')->latest()->get();
        return view('site.home', compact('categories','departments'));
    }
 
    // public function services(Request $request)
    // {
    //     // Lấy danh mục dịch vụ đang hoạt động
    //     $categories = Category::where('status', 1)->latest()->get();

    //     // Lấy danh sách dịch vụ
    //     $servicesQuery = Service::with(['category', 'department'])
    //         ->where('status', 1)
    //         ->latest();

    //     // Nếu có lọc theo danh mục
    //     if ($request->has('category') && $request->category != 'all') {
    //         $servicesQuery->where('category_id', $request->category);
    //     }

    //     $services = $servicesQuery->get();

    //     return view('site.services', compact('services', 'categories'));
    // }

public function services(Request $request)
{
    // Lấy danh mục dịch vụ đang hoạt động
    $categories = Category::where('status', 1)->latest()->get();

    // Lấy danh sách dịch vụ
    $servicesQuery = Service::with(['category', 'department'])
        ->where('status', 1)
        ->latest();

    // Nếu có lọc theo danh mục
    if ($request->has('category') && $request->category != 'all') {
        $servicesQuery->where('category_id', $request->category);
    }

    $services = $servicesQuery->get();

    // Lấy danh sách chuyên khoa đang hoạt động
    $departments = Department::where('status', 'active')->latest()->get();

    return view('site.services', compact('services', 'categories', 'departments'));
}

    public function serviceShow(Service $service)
    {
        return view('site.service_show', compact('service'));
    }

    // public function schedule()
    // {
       
    //     return view('site.schedule');
    // }
    public function schedule()
{
    // Lấy danh sách chuyên khoa đang hoạt động
    $departments = Department::where('status', 'active')->latest()->get();

    return view('site.schedule', compact('departments'));
}
     public function medical_records()
    {
       
        return view('site.medical_records');
    }
     public function payment()
    {
       
        return view('site.payment');
    }
    public function contact()
    {
       
        return view('site.contact');
    }

}
