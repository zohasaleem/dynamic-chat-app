<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;
use Session;

// use Spatie\Browsershot\Browsershot;
// use Illuminate\Support\Facades\File;
// use Spatie\PdfToImage\Pdf;
// use Imagick;


use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\User;


// use Barryvdh\DomPDF\Facade\Pdf;


use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

use Torann\GeoIP\Facades\GeoIP;


use App\Models\Book;
use App\Models\Author;

class PermissionController extends Controller
{


    public function index(){

        $roles = Role::all();
        $permissions = Permission::all();

        //practice n+1 query package
        //instead of this
        // $authors = Author::with('books')->get();
        //use
        $authors = Author::withCount('books')->get();

        return view('admin.permission', compact('roles', 'permissions', 'authors'));
    }


    public function grantPermissionToRole(Request $request)
    {
        $permissionName = $request->input('permission');
        $roleName = $request->input('role');


        $loggedInUser = auth()->user(); 

        $role = Role::where('name', $roleName)->first();

        $permission = Permission::where('name', $permissionName)->first();



        if ($role->hasPermissionTo($permission)) {
            // Log activity even if the permission is already assigned
            activity()
                ->causedBy($loggedInUser)
                ->performedOn($role)
                ->withProperties(['permission' => $permissionName])
                ->log("{$loggedInUser->name} attempted to grant {$permissionName} permission to {$role->name} role, but it was already assigned");
    
                flash('Permission is already assigned to the role');
                return redirect()->route('permission');
                // return redirect()->route('permission')->with('success', 'Permission is already assigned to the role');

            // Session::flash('warning', 'Permission is already assigned to the role');
        } else {

            $role->givePermissionTo($permission);


            //for activity catching
            activity()
                ->causedBy($loggedInUser)
                ->performedOn($role)
                ->withProperties(['permission' => $permissionName])
                ->log("{$loggedInUser->name} granted {$permissionName} permission to {$role->name} role");

                flash('Permission granted successfully and Activity Logged');
                return redirect()->route('permission');
                // return redirect()->route('permission')->with('success', 'Permission granted successfully and Activity Logged');
        }
    }


    //Package Browsershot
    public function browsershot(Request $req){

        // $pdf = Browsershot::url('https://laraveldaily.com/packages')
        // ->setNodeBinary("C:\Program Files\\nodejs\\node.exe")
        // ->setOption('newHeadless', true)
        // ->pdf();


        // $directory = public_path('/images');

        // if (!File::exists($directory)) {
        //     File::makeDirectory($directory, 0755, true);
        // }

        // $pdf = Browsershot::url('https://laraveldaily.com/packages')
        //     ->setNodeBinary("C:\Program Files\\nodejs\\node.exe")
        //     ->setOption('newHeadless', true)
        //     ->setOption('tempDir', $directory)
        //     ->pdf();
            // $pdf = Browsershot::url('https://laraveldaily.com/packages')
            // ->setNodeBinary("C:\Program Files\\nodejs\\node.exe")
            // ->setOption('tempDir', 'E:\\xampp\\htdocs\\dynamic-chatApp\\public\\images')
            // ->pdf();
        
        // $pdf = Browsershot::url('https://laraveldaily.com/packages')
        // ->setNodeBinary("C:\Program Files\\nodejs\\node.exe")
        // ->setOption('newHeadless', true)
        // ->setOption('tempDir', public_path('images'))
        // ->pdf();
    

// return $pdf;
        // $pdfPath = public_path('pdf/example.pdf');

        // Browsershot::url('https://laraveldaily.com/packages')->save($pdfPath);


    //pdf to image package
    // $pdf = new Spatie\PdfToImage\Pdf('file.pdf');
    // $files = public_path('images\Backend Task for Pre-Internship.pdf');
    // $pdf = new Pdf($files);


    // $pdf->saveImage( public_path('images'));




    //Excel Package
    // $users = User::all();
    // (new FastExcel($users))->export(public_path('images/foodDiabetesChanged.csv'));
    // $export = (new FastExcel(User::all()))->export(public_path('images\foodDiabetesChanged.csv'), function ($user) {
    //     return [
    //         'id' => $user->id,
    //         'name' => $user->name,
    //         'email' => strtoupper($user->email),
    //     ];
    // });
    // return  $export;



    //DomPDF
    // $data = [
    //     'invoice_number' => 'INV-123',
    //     'total_amount' => 100.00,
    //     // Include other necessary data here
    // ];

    // // Generate PDF from a view and prompt download
    // $pdf = PDF::loadView('invoice', $data);
    // return $pdf->download('invoice.pdf');

    //not working using App
    // $pdf = App::make('dompdf.wrapper');
    // $pdf->loadHTML('<h1>Test</h1>');
    // return $pdf->stream();


    

//Snappy htm to pdf/image
       
        // return PDF::loadFile('https://fitvisorapp.netlify.app/?fbclid=IwAR3djEH2CY231FkyX2WJZMJwHyMs2aLJYKUqtnG-cH9-_ZMutWHyBJVOYNI')->inline('image.pdf');
        // $data = [
        //     'invoice_number' => 'INV-123',
        //     'total_amount' => 100.00,
        //     // Include other necessary data here
        // ];
        // $pdf = PDF::loadView('invoice', $data);
        // return $pdf->download('test.pdf');


        //torann geo package
        // $info = geoip()->getLocation();
        // dd($info);
        // return $info->country . ', ' . $info->city;
        //         return geoip()->getLocation( $req->ip());


        //did fo package that helps using route names in js
        // $users = User::all();
        // return response()->json(["users" => $users]);
    
    }

    //Captcha
    // public function reloadCaptcha()
    // {
    //     return response()->json(['captcha'=> captcha_img()]);
    // }

    public function myCaptcha()
    {
        return view('captcha');
    }

    public function myCaptchaPost(Request $request)
    {
        request()->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ],
        ['captcha.captcha'=>'Invalid captcha code.']);
        dd("You are here :) .");
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }


    public function books()
    {
        // $books = Book::all();

        // foreach($books as $book){
        //     echo($book->author->first_name);
        // }

        //should do this

        $books = Book::with('author')->get();
        foreach($books as $book){
            echo($book->author->first_name);
        }
    

       
        return $books;
    }

    
}

