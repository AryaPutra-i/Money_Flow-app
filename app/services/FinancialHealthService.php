<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Debt;
use App\Models\FinancialHealthScore;
use Illuminate\Support\Carbon;

class FinancialHealthService{
    public function hitungDanSimpanSkor(int $workspaceId): FinancialHealthScore {
        $awalBulanIni = Carbon::now()->startOfMonth();
        $totalSaldoDompet = Wallet::where('workspace_id', $workspaceId)->sum('balance');

        $pemasukanBulanIni = Transaction::where('workspace_id', $workspaceId)
            ->where('type', 'income')
            ->where('created_at', '>=', $awalBulanIni)
            ->sum('amount');
        
        $pengeluaranBulanIni = Transaction::where('workspace_id', $workspaceId)
            ->where('type', 'expense')
            ->where('created_at', '>=', $awalBulanIni)
            ->sum('amount');

        $danaDaruratBulan = $pengeluaranBulanIni > 0 ? round($totalSaldoDompet / $pengeluaranBulanIni, 1) : 0;
        $debtToIncomeRatio = $pemasukanBulanIni > 0 ? round($totalHutangBelumLunas / $pemasukanBulanIni, 2) : 0;

        $sisaUang = $pemasukanBulanIni - $pengeluaranBulanIni;
        $savingRate = $pemasukanBulanIni > 0 ? round(($sisaUang / $pemasukanBulanIni) * 100, 2) : 0;

        $skor = 0;

        if($savingRate >= 20) $skor += 40;
        elseif ($savingRate > 0) $skor += 20;

        if($debtToIncomeRatio <= 0.30 && $pemasukanBulanIni > 0) $skor += 30;
        elseif ($debtToIncomeRatio <= 0.50 && $pemasukanBulanIni > 0) $skor += 15;

        if($danaDaruratBulan >= 3) $skor += 30;
        elseif ($danaDaruratBulan >= 1) $skor += 15;
        
        $rincianMetrik = [
            'total_aset_likuid' => 'Rp ' . number_format($totalSaldoDompet, 0, ',', '.'),
            'durasu_bertahan_dana_darurat' => $danaDaruratBulan . ' Bulan',
            'rasio_hutang_vs_income' => ($debtToIncomeRatio * 100) . '%',
            'rasio_menabung_bulan_ini' => $savingRate . '%',
            'kesimpulan_sistem' => $skor >= 80 ? 'sangat sehat' : 'Butuh Penyesuaian Anggaran',
        ];

        return FinancialHealthScore::create([
            'workspace_id' => $workspaceId,
            'score' => $skor,
            'rincian_metrik' => $rincianMetrik,
        ]);
    }
}
