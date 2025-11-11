<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ordersheet;
use App\Models\Timbangan_riwayat;
use App\Models\VAllOrdersheetPlusCari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WeightController extends Controller
{
    public function setCurrentId(Request $request)
    {
        $id = $request->input('id');
        Log::info('Set current ID', ['id' => $id]);
        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID tidak valid']);
        }

        Cache::put('current_id', $id, now()->addMinutes(5));
        return response()->json(['success' => true, 'current_id' => $id]);
    }

    public function cekIdAktif()
    {
        $id = Cache::get('current_id');
        return response()->json(['current_id' => $id]);
    }

    // public function terimaBerat(Request $request)
    // {
    //     $id = $request->id;
    //     $berat = $request->berat;

    //     if (!$id || $berat === null) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Data tidak lengkap'
    //         ], 400);
    //     }

    //     // GUNAKAN CACHE, BUKAN SESSION!
    //     Cache::put("weight_preview_{$id}", $berat, now()->addMinutes(10));

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Berat diterima',
    //         'berat' => $berat
    //     ]);
    // }

    public function terimaBerat(Request $request)
    {
        $id = $request->id;
        $berat = $request->berat;

        if (!$id || $berat === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak lengkap'
            ], 400);
        }

        // CACHE KEY KONSISTEN
        Cache::put("weight_preview_{$id}", $berat, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'message' => 'Berat diterima',
            'berat' => $berat
        ]);
    }

    public function getPreview($id)
    {
        $berat = Cache::get("weight_preview_{$id}");

        // Fallback: ambil dari riwayat jika cache kosong
        if ($berat === null) {
            $last = Timbangan_riwayat::where('id', $id)
                ->orderBy('created_at', 'desc')
                ->first();
            $berat = $last?->berat ?? 0;
        }

        return response()->json([
            'success' => true,
            'berat' => is_numeric($berat) ? floatval($berat) : 0
        ]);
    }

    // public function getPreview($id)
    // {
    //     // AMBIL DARI CACHE, BUKAN SESSION!
    //     $berat = Cache::get("weight_preview_{$id}", 0);

    //     // Log::info("Preview requested for ID: {$id}, Berat: {$berat}");

    //     return response()->json([
    //         'success' => true,
    //         'berat' => $berat
    //     ]);
    // }

    public function preview(Request $request)
    {
        $id = $request->id;
        $berat = $request->berat;

        if (!$id || !$berat) {
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap'], 400);
        }

        $key = 'timbang_preview_' . $id;

        // Simpan ke cache untuk sementara (5 menit)
        Cache::put($key, ['berat' => $berat], 300);

        // Simpan juga ke tabel riwayat
        Timbangan_riwayat::create([
            'id' => $id,
            'berat'  => $berat,
            'tanggal' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function getRiwayat()
    {
        $records = Timbangan_riwayat::latest()->take(20)->get();

        return response()->json([
            'success' => true,
            'data' => $records,
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'Order_code' => 'required|string',
            'Buyer'      => 'required|string',
            'berat'      => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan / update Ordersheet
            $ordersheet = Ordersheet::updateOrCreate(
                ['Order_code' => $request->Order_code],
                [
                    'Buyer'               => $request->Buyer,
                    'PO'                  => $request->PO,
                    'Style'               => $request->Style,
                    'Qty_order'           => $request->Qty_order,
                    'Carton_weight_std'   => $request->Carton_weight_std,
                    'Pcs_weight_std'      => $request->Pcs_weight_std,
                    'PCS'                 => $request->PCS,
                    'Ctn'                 => $request->Ctn,
                    'Less_Ctn'            => $request->Less_Ctn,
                    'Pcs_Less_Ctn'        => $request->Pcs_Less_Ctn,
                    'Gac_date'            => $request->Gac_date,
                    'Destination'         => $request->Destination,
                    'Inspector'           => $request->Inspector,
                    'OPT_QC_TIMBANGAN'    => $request->OPT_QC_TIMBANGAN ?? Auth::user()->username,
                    'SPV_QC'              => $request->SPV_QC,
                    'CHIEF_FINISH_GOOD'   => $request->CHIEF_FINISH_GOOD,
                    'status'              => 'Success'
                ]
            );

            // 2. Ambil berat
            $berat = $request->filled('berat') ? floatval($request->berat) : 0.00;

            // 3. Simpan riwayat timbangan
            Timbangan_riwayat::create([
                'id_ordersheet'              => $ordersheet->id,
                'berat'                      => $berat,
                'no_box'                     => $request->no_box,
                'rasio_batas_beban_min'      => $request->rasio_batas_beban_min ?? null,
                'rasio_batas_beban_max'      => $request->rasio_batas_beban_max ?? null,
                'status'                     => 'Success',
                'waktu_timbang'              => now(),
            ]);

            // 4. Simpan ke view table (jika memang pakai model)
            VAllOrdersheetPlusCari::updateOrCreate(
                ['Order_code' => $request->Order_code],
                [
                    'Buyer'               => $request->Buyer,
                    'PurchaseOrderNumber' => $request->PO,
                    'ProductName'         => $request->Style,
                    'Qty'                 => $request->Qty_order,
                    'DestinationCountry'  => $request->Destination,
                    'GAC'                 => $request->Gac_date,
                    'FinalDestination'    => $request->Destination,
                    'status'              => 'Success',
                    'cari'                => $request->Buyer . ' ' . $request->Order_code . ' ' . $request->PO,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $berat > 0
                    ? "Data berhasil disimpan dengan berat: {$berat} kg!"
                    : "Data berhasil disimpan (tanpa berat timbangan)"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error simpan ordersheet: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
