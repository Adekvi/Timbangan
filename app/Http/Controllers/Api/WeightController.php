<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ordersheet;
use App\Models\Timbangan_riwayat;
use App\Models\Update\Device;
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
            return response()->json([
                'success' => false,
                'message' => 'ID tidak valid'
            ]);
        }

        // Hapus semua cache berat lama sebelum set ID baru
        $oldId = Cache::get('current_id');
        if ($oldId && $oldId !== $id) {
            Cache::forget("weight_preview_{$oldId}");
            Cache::forget("timbang_preview_{$oldId}");
            Log::info("Cache berat untuk ID lama {$oldId} dihapus.");
        }

        // Set ID baru ke cache
        Cache::put('current_id', $id, now()->addMinutes(5));

        // Reset berat ID baru ke 0
        Cache::put("weight_preview_{$id}", 0, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'message' => 'ID aktif diubah',
            'current_id' => $id
        ]);
    }

    public function cekIdAktif()
    {
        $id = Cache::get('current_id');

        return response()->json([
            'current_id' => $id
        ]);
    }
    
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

        // Cache::put("weight_preview_{$id}", $berat, now()->addMinutes(10));
        Cache::put("weight_preview_{$id}", $berat, now()->addSeconds(2));

        return response('OK', 200);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Berat diterima',
        //     'berat' => $berat
        // ]);
    }

    public function getPreview($id)
    {
        $berat = Cache::get("weight_preview_{$id}", 0);

        return response()->json([
            'success' => true,
            'berat' => $berat
        ]);
    }

    public function preview(Request $request)
    {
        $id = $request->id;
        $berat = $request->berat;

        if (!$id || $berat === null) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap'
            ], 400);
        }

        $key = "timbang_preview_{$id}";

        Cache::put($key, ['berat' => $berat], now()->addMinutes(7));

        return response()->json([
            'success' => true,
            'message' => 'Berat disimpan ke riwayat'
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'Order_code' => 'required|string',
            'Buyer'      => 'required|string',
            'berat'      => 'required|numeric|min:0',
            'no_box'     => 'required',
            'rasio_batas_beban_min' => 'required|numeric',
            'rasio_batas_beban_max' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {

            $existingOrdersheet = Ordersheet::where('Order_code', $request->Order_code)->first();
            $existingV = VAllOrdersheetPlusCari::where('Order_code', $request->Order_code)->first();

            $device = Device::where('user_id', Auth::id())
                ->where('status', 'in_use')
                ->first();

            $ordersheet = Ordersheet::updateOrCreate(
                ['Order_code' => $request->Order_code],
                [
                    'id_user'             => Auth::id(),
                    'id_device'           => $device?->id,
                    'Buyer'               => $request->Buyer ?? $existingOrdersheet?->Buyer,
                    'PO'                  => $request->PO ?? $existingOrdersheet?->PO,
                    'Style'               => $request->Style ?? $existingOrdersheet?->Style,
                    'Qty_order'           => $request->Qty_order ?? $existingOrdersheet?->Qty_order,
                    'Carton_weight_std'   => $request->Carton_weight_std ?? $existingOrdersheet?->Carton_weight_std,
                    'Pcs_weight_std'      => $request->Pcs_weight_std ?? $existingOrdersheet?->Pcs_weight_std,
                    'PCS'                 => $request->PCS ?? $existingOrdersheet?->PCS,
                    'Ctn'                 => $request->Ctn ?? $existingOrdersheet?->Ctn,
                    'Less_Ctn'            => $request->Less_Ctn ?? $existingOrdersheet?->Less_Ctn,
                    'Pcs_Less_Ctn'        => $request->Pcs_Less_Ctn ?? $existingOrdersheet?->Pcs_Less_Ctn,
                    'Gac_date'            => $request->Gac_date ?? $existingOrdersheet?->Gac_date,
                    'Destination'         => $request->Destination ?? $existingOrdersheet?->Destination,
                    'Inspector'           => $request->Inspector ?? $existingOrdersheet?->Inspector,
                    'OPT_QC_TIMBANGAN'    => $request->OPT_QC_TIMBANGAN ?? Auth::user()->username,
                    'SPV_QC'              => $request->SPV_QC ?? $existingOrdersheet?->SPV_QC,
                    'CHIEF_FINISH_GOOD'   => $request->CHIEF_FINISH_GOOD ?? $existingOrdersheet?->CHIEF_FINISH_GOOD,
                    'status'              => 'Success'
                ]
            );

            $berat = floatval($request->berat);

            Timbangan_riwayat::create([
                'id_user'                    => Auth::id(),
                'id_device'                  => $device?->id,
                'id_ordersheet'              => $ordersheet->id,
                'berat'                      => $berat,
                'no_box'                     => $request->no_box,
                'rasio_batas_beban_min'      => $request->rasio_batas_beban_min,
                'rasio_batas_beban_max'      => $request->rasio_batas_beban_max,
                'status'                     => 'Success',
                'waktu_timbang'              => now(),
            ]);

            VAllOrdersheetPlusCari::updateOrCreate(
                ['Order_code' => $request->Order_code],
                [
                    'Buyer'               => $request->Buyer ?? $existingV?->Buyer,
                    'PurchaseOrderNumber' => $request->PO ?? $existingV?->PurchaseOrderNumber,
                    'ProductName'         => $request->Style ?? $existingV?->ProductName,
                    'Qty'                 => $request->Qty_order ?? $existingV?->Qty,
                    'DestinationCountry'  => $request->Destination ?? $existingV?->DestinationCountry,
                    'GAC'                 => $request->Gac_date ?? $existingV?->GAC,
                    'FinalDestination'    => $request->Destination ?? $existingV?->FinalDestination,
                    'status'              => 'Success',
                    'cari'                => ($request->Buyer ?? $existingV?->Buyer)
                                            . ' ' . $request->Order_code . ' '
                                            . ($request->PO ?? $existingV?->PurchaseOrderNumber),
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $berat > 0
                    ? "Data berhasil disimpan dengan berat: {$berat} kg!"
                    : "Data berhasil disimpan (tanpa berat timbangan)",
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

    public function getRiwayat()
    {
        $records = Timbangan_riwayat::latest()->take(20)->get();

        return response()->json([
            'success' => true,
            'data' => $records,
        ]);
    }
}
