<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_karyawan)){
            $query->where('nama_lengkap','like','%' . $request->nama_karyawan . '%');
        }
        $karyawan = $query->paginate(2);
        return view('karyawan.index',compact('karyawan'));
    }

}