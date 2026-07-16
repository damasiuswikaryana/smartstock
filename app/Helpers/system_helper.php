<?php

use App\Models\Outlet;
use App\Models\StockMutation;
use App\Models\StockInMaster;

function storeMutation(string $tipe, string $pekerjaan_id, string $source, mixed $source_id, string $target, mixed $target_id, int $item_id, int $item_qty, string $keterangan, int $entitas_id)
{
    try {
        DB::beginTransaction();
        $insert = StockMutation::create([
            'entitas_id'            => $entitas_id,
            'pekerjaan_id'          => $pekerjaan_id,
            'source_type'           => $source,
            'source_id'             => $source_id,
            'target_type'           => $target,
            'target_id'             => $target_id,
            'item_id'               => $item_id,
            'jumlah'                => $item_qty,
            'tipe'                  => $tipe,
            'keterangan'            => $keterangan,
        ]);
        DB::commit();
        return "success";
    } catch (\Throwable $th) {
        DB::rollback();
        return "Error: " . $th->getMessage();
    }
}

function namaLokasi(int $id)
{
    $q = Outlet::where('id', $id)->first();
    return $q->nama;
}
