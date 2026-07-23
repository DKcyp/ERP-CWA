@extends('layouts.layout')

@section('title', 'Main Dashboard')

@section('content')
<div class="page-content">
    {{-- KPI Cards Row with Logo Inspired Colors --}}
    <div class="row g-3 mb-4">
        {{-- Earnings (Royal Blue) --}}
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="hz-card d-flex align-items-center gap-3">
                <div class="hz-icon-container hz-icon-blue">
                    <i class="bi bi-bar-chart-line-fill"></i>
                </div>
                <div>
                    <div class="hz-kpi-label">Earnings</div>
                    <div class="hz-kpi-value">$340.5</div>
                </div>
            </div>
        </div>

        {{-- Spend this month (Amber Gold) --}}
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="hz-card d-flex align-items-center gap-3">
                <div class="hz-icon-container hz-icon-amber">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <div>
                    <div class="hz-kpi-label">Spend this month</div>
                    <div class="hz-kpi-value">$642.39</div>
                </div>
            </div>
        </div>

        {{-- Sales (Lime Green) --}}
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="hz-card d-flex align-items-center gap-3">
                <div class="hz-icon-container hz-icon-lime">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <div class="hz-kpi-label">Sales</div>
                    <div class="hz-kpi-value">$574.34</div>
                </div>
            </div>
        </div>

        {{-- Your Balance (Magenta Pink) --}}
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="hz-card d-flex align-items-center gap-3">
                <div class="hz-icon-container hz-icon-pink">
                    <i class="bi bi-grid-fill"></i>
                </div>
                <div>
                    <div class="hz-kpi-label">Your Balance</div>
                    <div class="hz-kpi-value">$1,000</div>
                </div>
            </div>
        </div>

        {{-- New Tasks (Crimson Red) --}}
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="hz-card d-flex align-items-center gap-3">
                <div class="hz-icon-container hz-icon-red">
                    <i class="bi bi-file-earmark-check-fill"></i>
                </div>
                <div>
                    <div class="hz-kpi-label">New Tasks</div>
                    <div class="hz-kpi-value">145</div>
                </div>
            </div>
        </div>

        {{-- Total Projects (Sky Cyan) --}}
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="hz-card d-flex align-items-center gap-3">
                <div class="hz-icon-container hz-icon-cyan">
                    <i class="bi bi-house-door-fill"></i>
                </div>
                <div>
                    <div class="hz-kpi-label">Total Projects</div>
                    <div class="hz-kpi-value">$2433</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Charts Section --}}
    <div class="row g-4 mb-4">
        {{-- Total Spent Line Chart --}}
        <div class="col-12 col-lg-7 col-xl-8">
            <div class="hz-chart-card h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <button class="hz-badge-btn mb-2">
                            <i class="bi bi-calendar-event me-1"></i> This month
                        </button>
                        <h2 class="fw-bold mb-0 text-dark">$37.5K</h2>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="text-muted fs-7">Total Spent</span>
                            <span class="badge bg-light-success text-success fw-bold">
                                <i class="bi bi-caret-up-fill me-1"></i>+2.45%
                            </span>
                        </div>
                    </div>
                    <div class="hz-stat-icon">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                </div>
                <div style="height: 240px; position: relative;">
                    <canvas id="totalSpentChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Weekly Revenue Bar Chart --}}
        <div class="col-12 col-lg-5 col-xl-4">
            <div class="hz-chart-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0" style="color: #1B2559;">Weekly Revenue</h5>
                    <div class="hz-stat-icon">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>
                </div>
                <div style="height: 240px; position: relative;">
                    <canvas id="weeklyRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Users Table --}}
    <div class="row">
        <div class="col-12">
            <div class="hz-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: #1B2559;">Recent Registered Users</h5>
                    <span class="badge rounded-pill px-3 py-2 fw-semibold" style="background: #EFF6FF; color: #2563EB;">Active Staff</span>
                </div>
                <div class="table-responsive">
                    <table class="table hz-table align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers as $user)
                            <tr>
                                <td class="fw-bold text-dark">{{ $user->name }}</td>
                                <td class="text-muted">{{ $user->username }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #EFF6FF; color: #2563EB;">
                                        {{ $user->roles->role_name ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $user->department ?? '-' }}</td>
                                <td class="text-muted">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Line Chart (Total Spent) using Royal Blue and Magenta Pink
        const ctxLine = document.getElementById('totalSpentChart').getContext('2d');
        const gradientBlue = ctxLine.createLinearGradient(0, 0, 0, 240);
        gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
        gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        const gradientPink = ctxLine.createLinearGradient(0, 0, 0, 240);
        gradientPink.addColorStop(0, 'rgba(236, 72, 153, 0.25)');
        gradientPink.addColorStop(1, 'rgba(236, 72, 153, 0.0)');

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['SEP', 'OCT', 'NOV', 'DEC', 'JAN', 'FEB'],
                datasets: [
                    {
                        label: 'Spent',
                        data: [50, 64, 48, 66, 49, 68],
                        borderColor: '#2563EB',
                        borderWidth: 4,
                        tension: 0.45,
                        pointRadius: 0,
                        fill: true,
                        backgroundColor: gradientBlue
                    },
                    {
                        label: 'Earnings',
                        data: [30, 40, 24, 46, 20, 46],
                        borderColor: '#EC4899',
                        borderWidth: 4,
                        tension: 0.45,
                        pointRadius: 0,
                        fill: true,
                        backgroundColor: gradientPink
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#A3AED0', font: { weight: 'bold' } } },
                    y: { display: false }
                }
            }
        });

        // Bar Chart (Weekly Revenue) using Royal Blue, Amber Gold, Pink
        const ctxBar = document.getElementById('weeklyRevenueChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['17', '18', '19', '20', '21', '22', '23', '24', '25'],
                datasets: [
                    {
                        label: 'Revenue 1',
                        data: [25, 30, 22, 35, 20, 28, 26, 32, 29],
                        backgroundColor: '#2563EB',
                        borderRadius: 20,
                        barThickness: 12
                    },
                    {
                        label: 'Revenue 2',
                        data: [15, 20, 14, 22, 12, 18, 16, 20, 18],
                        backgroundColor: '#F59E0B',
                        borderRadius: 20,
                        barThickness: 12
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { stacked: true, grid: { display: false }, ticks: { color: '#A3AED0', font: { weight: 'bold' } } },
                    y: { stacked: true, display: false }
                }
            }
        });
    });
</script>
@endpush
@endsection
