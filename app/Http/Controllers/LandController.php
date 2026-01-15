<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\LahanRawData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandController extends Controller
{
    public function index(Request $request)
    {
        $query = Lahan::query();

        if ($request->filled('village')) {
            $query->where('village', $request->village);
        }

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('source')) {
            $query->where('source_data', $request->source);
        }

        $data = $query->orderBy('id', 'asc')->paginate(20);

        $villages = Lahan::distinct('village')
            ->where('village', '!=', null)
            ->orderBy('village')
            ->pluck('village');

        $kecamatan = Lahan::distinct('kecamatan')
            ->where('kecamatan', '!=', null)
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        $cities = Lahan::distinct('city')
            ->where('city', '!=', null)
            ->orderBy('city')
            ->pluck('city');

        $stats = [
            'total_lahan' => Lahan::count(),
            'total_petani' => Lahan::whereNotNull('farmer_no')
                ->distinct('farmer_no')
                ->count(),
            'lahan_geko' => Lahan::where('source_data', 'GEKO')->count(),
            'lahan_bhl' => Lahan::where('source_data', 'BHL')->count(),
            'duplicates' => Lahan::where('is_duplicate', true)->count(),
            'new_from_bhl' => Lahan::where('source_data', 'BHL')
                ->where('is_duplicate', false)
                ->count(),
        ];

        $duplicate = LahanRawData::select('lahan_no')
            ->groupBy('lahan_no')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('lahan_no')
            ->toArray();

        $data->getCollection()->transform(function ($item) use ($duplicate) {
            if (in_array($item->lahan_no, $duplicate)) {
                $item->status_merge = 'duplicated';
            } elseif ($item->source_data === 'GEKO') {
                $item->status_merge = 'existing';
            } else {
                $item->status_merge = 'new';
            }

            return $item;
        });

        return view('lands.index', compact(
            'data',
            'villages',
            'kecamatan',
            'cities',
            'stats',
            'duplicate'
        ));
    }
}
