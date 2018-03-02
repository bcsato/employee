<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Input;
use Validator;
use Redirect;
use Session;
use File;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = DB::table('emp_details')->where('deleted','0')->paginate(3);
        return view('list',['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Custom validation rules
        $validator = Validator::make($request->all(),[
            'name' => 'required | max:30',
            'email' => 'required | max:50 | email',
            'address'=>'required | max:250',
            'emp_image' => 'required|mimes:jpg,png,jpeg|max:2000',
            'gender'=>'required'
        ]);        
        
        //Check if the validation fails or not
        if ($validator->fails()) 
        {
         return redirect('employee/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        else
        {
            $name    = $request->name;
            $email   = $request->email;
            $gender  = $request->gender;
            $address = $request->address;
            


            // checking file is valid.
            if (Input::file('emp_image')->isValid()) 
            {
              $extension = Input::file('emp_image')->getClientOriginalExtension(); 
              $image_name = Input::file('emp_image')->getClientOriginalName(); 
              $fileName = time().'_'.$image_name;
              Input::file('emp_image')->move(public_path('uploads') . '/', $fileName); 

               //Insert array
              $data_insert['emp_name']    = $name;
              $data_insert['emp_email']   = $email;
              $data_insert['emp_gender']  = $gender;
              $data_insert['emp_address'] = $address;
              $data_insert['emp_image']   = $fileName;
              $data_insert['created_on']  = date('Y-m-d H:i:s');

              //Insert the record into the database
             if(DB::table('emp_details')->insert($data_insert))
             {
                Session::flash('success', 'Record saved successfully');
                return Redirect::to('employee');  
             }
             else
             {
                Session::flash('error', 'Something went wrong, please try again');
                return Redirect::to('employee/create');
             }
              
            }
            else 
            {
              Session::flash('error', 'Uploaded file is not valid, please check image size or extension');
              return Redirect::to('employee/create');
            }
        }

    }

    /**
     * Display the specified resource.
     *  
     * @param  int  $id
     * @return \Illuminate\Http\m_responsekeys(conn, identifier)
     */
    public function show($id)
    {
        if($id!='')
        {
            $data_update = array('deleted'=>'0','emp_id'=>$id);
            $employee = DB::table('emp_details')->where($data_update)->get()->first();
            return view('edit',['employee' => $employee]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Custom validation rules
        $validator = Validator::make($request->all(),[
            'name' => 'required | max:30',
            'email' => 'required | max:50 | email',
            'address'=>'required | max:250',
            'gender'=>'required',
            'emp_image' => 'mimes:jpg,png,jpeg|max:2000',
        ]);        
        

        //Check if the validation fails or not
        if ($validator->fails()) 
        {
         return redirect('employee/show/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
        else
        {
              $name    = $request->name;
              $email   = $request->email;
              $gender  = $request->gender;
              $address = $request->address;
              
              //If new image is not updated get the old image
              if(!Input::file('emp_image'))
              {
                 $data_update['emp_image']   = $request['old_img'];
              }
              else
              {
                // checking file is valid.
                if (Input::file('emp_image')->isValid()) 
                {
                    $file    = array('image' => Input::file('emp_image'));
                    $extension = Input::file('emp_image')->getClientOriginalExtension(); 
                    $image_name = Input::file('emp_image')->getClientOriginalName(); 
                    $fileName = time().'_'.$image_name;
                    $data_update['emp_image']   = $fileName;

                    //Unlink the old image
                    $old_img = $request['old_img'];

                    //Unlink the old image
                    $old_img_path = public_path().'/uploads/'.$old_img;
                    File::delete($old_img_path);

                    //Move the new file
                    Input::file('emp_image')->move(public_path('uploads') . '/', $fileName); 
                }
                else 
                {
                    Session::flash('error', 'Uploaded file is not valid, please check image size or extension');
                    return Redirect::to('employee/edit');
                }

              }
            
               //Update array
              $data_update['emp_name']    = $name;
              $data_update['emp_email']   = $email;
              $data_update['emp_gender']  = $gender;
              $data_update['emp_address'] = $address;
              $data_update['modified_on']  = date('Y-m-d H:i:s');

              //Update the record into the database
             if(DB::table('emp_details')->where('emp_id', $id)->update($data_update))
             {
                Session::flash('success', 'Record updated successfully');
                return Redirect::to('employee');  
             }
             else
             {
                Session::flash('error', 'Something went wrong, please try again');
                return Redirect::to('employee/show/'.$id);
             }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id!='')
        {
            if(DB::table('emp_details')
            ->where('emp_id', $id)
            ->update(['deleted' => 1])) {
                Session::flash('success', 'Record deleted successfully');
                return Redirect::to('employee');  
            }
            else
            {
                Session::flash('danger', 'Something went wrong, please try again');
                return Redirect::to('employee');  
            }
        }
    }

    /**
    ** Function is used to viewing particular employee
    **/
    public function view($id)
    {
        if($id!='')
        {
            $data_view = array('deleted'=>'0','emp_id'=>$id);
            $employee = DB::table('emp_details')->where($data_view)->get()->first();
            return view('view',['employee' => $employee]);
        }
    }
}