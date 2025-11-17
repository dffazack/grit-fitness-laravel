@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('page-title', 'Laporan Keuangan')
@section('page-subtitle', 'Rincian pendapatan bulanan.')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="card-title-custom mb-0">
                Laporan Pendapatan Bulanan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Bulan</th>
                            <th class="border-0 text-end">Total Transaksi</th>
                            <th class="border-0 text-end">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyReports as $report)
                            <tr>
                                <td>
                                    <strong>{{ \Carbon\Carbon::create()->month($report->month)->format('F') }} {{ $report->year }}</strong>
                                </td>
                                <td class="text-end">{{ $report->total_transactions }}</td>
                                <td class="text-end text-success fw-semibold">Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Belum ada data transaksi yang disetujui.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($monthlyReports->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $monthlyReports->links() }}
            </div>
        @endif
    </div>
@endsection
