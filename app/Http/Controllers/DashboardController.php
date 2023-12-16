<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function patient($date)
    {
        $user = auth()->user();

        if (!$user || $user == null) {
            return response()->json([
                'errors' => [
                    'message' => [
                        'unauthorized',
                    ],
                ],
            ], 401);
        }

        if (Gate::denies('Akses Admin')) {
            return response()->json([
                'errors' => [
                    'message' => [
                        'You dont have permission to this action',
                    ],
                ],
            ], 403);
        }

        $doctor = Doctor::all();
        $date_fix = Carbon::parse($date);
        $doctor->load(['appointments' => function ($query) use ($date_fix) {
            $query->where('appointment_date', $date_fix->format('Y-m-d'));
        }]);

        
       return response()->json([
           'message' => 'Success',
           'data' => $doctor,
           "tanggal" => $date_fix->format('Y-m-d'),
       ], 200);
    }
}
