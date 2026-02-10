@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, #252083, #095664);
                   border-radius: 22px;">

            <div class="card-body text-white p-4">
                <!-- padding dikecilkan -->
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <!-- TYPEWRITER TEXT -->
                        <h2 id="welcomeText" class="fw-bold mb-1" style="font-size: 1.9rem; line-height:1.25;">
                        </h2>

                        <p class="mb-0 opacity-75" style="font-size: 1.05rem;">
                            <span id="welcomeSub"></span>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- ===== TYPEWRITER SCRIPT ===== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

    const text = "Selamat Datang, {{ Auth::user()->name }} 👋";
    const subText = "Anda berada di Panel Dashboard Admin";
    const target = document.getElementById("welcomeText");
    const subTarget = document.getElementById("welcomeSub");

    let index = 0;
    let subIndex = 0;

    function type(el, str, i, speed, cb) {
        if (!el) return typeof cb === 'function' && cb();
        el.classList.add('type-cursor');
        if (i < str.length) {
            el.innerHTML += str.charAt(i);
            setTimeout(() => type(el, str, i + 1, speed, cb), speed);
        } else {
            el.classList.remove('type-cursor');
            if (typeof cb === 'function') cb();
        }
    }

    // Reveal function: animate dashboard elements from inside-out
    let _revealTriggered = false;

    // Prepare list of elements to reveal and set them hidden immediately
    const _statCards = Array.from(document.querySelectorAll('.stat-card'));
    const _chartCards = Array.from(document.querySelectorAll('.col-md-6.mb-4 > .card'));
    const _revealElements = _statCards.concat(_chartCards);

    // Initially hide all target elements right away so they're invisible during typing
    _revealElements.forEach(el => el.classList.add('reveal-item'));

    function revealDashboardElements() {
        if (_revealTriggered) return;
        _revealTriggered = true;

        // Staggered reveal: add 'show' class with small delay between items
        _revealElements.forEach((el, i) => {
            setTimeout(() => el.classList.add('show'), i * 110);
        });
    }

    // Start: type header, then type subtext, then reveal dashboard
    type(target, text, 0, 40, function() {
        // small delay before starting subtext
        setTimeout(() => {
            type(subTarget, subText, 0, 35, revealDashboardElements);
        }, 250);
    });

    // Bind navigation for any card with data-href once DOM ready
    document.querySelectorAll('.card[data-href]').forEach(card => {
        // allow keyboard activation
        card.addEventListener('keydown', (ev) => {
            if (ev.key === 'Enter' || ev.key === ' ') {
                ev.preventDefault();
                const href = card.getAttribute('data-href');
                if (href) window.location.href = href;
            }
        });
        card.addEventListener('click', (ev) => {
            // Prevent clicks when interacting with internal controls
            const href = card.getAttribute('data-href');
            if (!href) return;
            window.location.href = href;
        });
    });
});
</script>




<style>
    .stat-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        height: 88px;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        transition: 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.08);
    }

    .stat-icon-side {
        /* reduce container so icons are a bit smaller */
        width: 80px;
        min-width: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        /* reduced responsive bounds for a smaller max size */
        font-size: clamp(18px, 2.4vw, 28px);
        color: white;
    }

    /* Ensure the <i> inside uses the computed size and stays centered */
    .stat-icon-side i {
        font-size: 1em;
        line-height: 1;
        display: inline-block;
        transform: translateZ(0);
    }

    .stat-body {
        padding: 10px 14px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-title {
        font-size: 12px;
        color: #9ca3af;
        letter-spacing: 0.5px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .stat-number {
        font-size: 20px;
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: -0.2px;
        font-variant-numeric: tabular-nums;
        -webkit-font-feature-settings: 'tnum';
        font-feature-settings: 'tnum';
        color: #111827;
    }

    /* gradients */
    .grad-info {
        background: linear-gradient(135deg, #06b6d4, #3b82f6);
    }

    .grad-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
    }

    .grad-success {
        background: linear-gradient(135deg, #10b981, #22c55e);
    }

    .grad-warning {
        background: linear-gradient(135deg, #f59e0b, #f97316);
    }

    .type-cursor::after {
        content: "|";
        margin-left: 4px;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    /* Reveal-from-center animation (used after typewriter finishes) */
    .reveal-item {
        opacity: 0;
        transform: translateY(12px) scale(0.98);
        transform-origin: center center;
        transition: opacity 360ms cubic-bezier(.2, .9, .2, 1), transform 420ms cubic-bezier(.2, .9, .2, 1);
        will-change: opacity, transform;
    }

    .reveal-item.show {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    /* Slight pop for the chart center total when charts reveal */
    .chart-center-total {
        transition: transform 420ms cubic-bezier(.2, .9, .2, 1), opacity 320ms ease;
        opacity: 0;
        transform: scale(0.92);
    }

    .reveal-item.show~.card .chart-center-total,
    .reveal-item.show+.chart-card-body .chart-center-total,
    .col-md-6 .card.reveal-item.show .chart-center-total {
        opacity: 1;
        transform: scale(1);
    }
</style>


<div class="row g-3">
    <!-- Wijk -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card stat-card reveal-item" data-href="{{ route('admin.wijk') }}" role="link" tabindex="0">
            <div class="d-flex h-100">
                <div class="stat-icon-side grad-info">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-title">Total Wijk</div>
                    <div class="stat-number">
                        {{ number_format($total_wijks ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Penatua -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card stat-card reveal-item" data-href="{{ route('admin.penatua') }}" role="link" tabindex="0">
            <div class="d-flex h-100">
                <div class="stat-icon-side grad-primary">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-title">Total Penatua</div>
                    <div class="stat-number">
                        {{ number_format($total_penatua ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendeta -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card stat-card reveal-item" data-href="{{ route('admin.pendeta') }}" role="link" tabindex="0">
            <div class="d-flex h-100">
                <div class="stat-icon-side grad-success">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-title">Total Pendeta</div>
                    <div class="stat-number">
                        {{ number_format($total_pendeta ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jemaat -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card stat-card reveal-item" data-href="{{ route('admin.jemaat') }}" role="link" tabindex="0">
            <div class="d-flex h-100">
                <div class="stat-icon-side grad-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-title">Jemaat Wijk</div>
                    <div class="stat-number">
                        {{ number_format($jemaats_in_wijk_count ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row mt-4">
    {{-- Perbandingan Keluarga per Wijk (kiri) --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm reveal-item" data-href="{{ route('admin.keluarga.create') }}" role="link"
            tabindex="0">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center flex-wrap">
                <strong>Perbandingan Keluarga per Wijk</strong>
            </div>
            <div
                class="card-body chart-card-body chart-left d-flex justify-content-center align-items-center flex-column">
                <canvas id="keluargaWijkChart" width="250" height="250"></canvas>
            </div>
        </div>
    </div>

    {{-- Distribusi Jenis Kelamin Jemaat (kanan) --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm gender-card reveal-item" data-href="{{ route('admin.jemaat') }}" role="link"
            tabindex="0">
            <div class="card-header text-white">
                <strong>Distribusi Jenis Kelamin Jemaat</strong>
            </div>
            <div class="card-body chart-card-body d-flex justify-content-center align-items-center flex-column">
                <canvas id="genderChart" width="250" height="250"></canvas>
                <div id="genderTotalCenter" class="chart-center-total"></div>
                <div id="genderBreakdown" class="mt-2 small text-muted d-flex gap-3 justify-content-center flex-wrap">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Satukan ukuran chart */
    .card-body canvas {
        max-width: 300px !important;
        max-height: 300px !important;
        width: 100% !important;
        height: auto !important;
    }

    /* Make both chart boxes equal and larger to match visual target */
    .chart-card-body,
    .chart-left {
        height: 420px;
        padding-bottom: 56px;
    }

    /* allow absolute children centered over the chart */
    .chart-card-body {
        position: relative;
    }

    .chart-center-total {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        text-align: center;
        line-height: 1;
    }

    .chart-center-total .label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .chart-center-total .value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #222;
        font-variant-numeric: tabular-nums;
        -webkit-font-feature-settings: 'tnum';
        font-feature-settings: 'tnum';
    }

    /* Right card (gender) - header blue, body dark */
    .gender-card .card-header {
        background: #0d6efd;
        /* Bootstrap primary blue */
        color: #fff;
    }

    .gender-card .card-body {
        background: #000;
        color: #fff;
    }

    .gender-card .text-muted {
        color: rgba(255, 255, 255, 0.75) !important;
    }

    .gender-card #genderBreakdown .text-center {
        /* kept for any remaining text-center elements; specific boxes use .gender-box */
        color: #fff;
    }

    .gender-card #genderBreakdown .gender-box {
        background: #ffffff;
        color: #212529;
        border-radius: 8px;
        min-width: 120px;
        cursor: pointer;
    }

    .gender-card #genderBreakdown .gender-box .gender-label {
        color: #212529;
        font-weight: 600;
    }

    .gender-card #genderBreakdown .gender-box .gender-value {
        color: #111;
        font-weight: 700;
    }

    .gender-card .chart-center-total {
        background: #000;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.45);
    }

    .gender-card .chart-center-total .value {
        color: #fff !important;
    }

    .gender-card #genderBreakdown .gender-box.disabled {
        opacity: 0.45;
        filter: grayscale(60%);
    }

    /* Clickable card visual affordance */
    .card[role="link"] {
        cursor: pointer;
        transition: transform 150ms ease, box-shadow 150ms ease;
    }

    .card[role="link"]:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .card[role="link"]:focus {
        outline: 3px solid rgba(13, 110, 253, 0.12);
        outline-offset: 2px;
    }
</style>


@push('script')
<!-- Chart.js is loaded in the main layout; initialize charts after DOM ready -->
<style>
    /* Make chart card bodies equal height and center the canvas */
    .chart-card-body {
        height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-card-body canvas {
        width: 100% !important;
        max-width: 300px;
        height: auto !important;
        max-height: 300px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Prepare chart data from server
    const keluargaLabels = @json($keluarga_labels ?? []);
    const keluargaValues = @json($keluarga_values ?? []);
    const genderLabels = @json($gender_labels ?? []);
    const genderValues = @json($gender_values ?? []);

    // Keluarga per Wijk - bar chart (use integer Y ticks, descending from max to 0)
    const elKeluarga = document.getElementById('keluargaWijkChart');
    if (elKeluarga && typeof Chart !== 'undefined') {
        const ctxKeluarga = elKeluarga.getContext ? elKeluarga.getContext('2d') : elKeluarga;

        // use wijk names as category labels on the x-axis
        const categoryLabels = keluargaLabels.slice();

        const maxVal = keluargaValues.length ? Math.max(...keluargaValues) : 0;
        const yMax = Math.ceil(maxVal);
        const step = 1; // integer steps

        new Chart(ctxKeluarga, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Keluarga',
                    data: keluargaValues,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: yMax,
                        ticks: {
                            stepSize: step,
                            callback: function(value) { return Number(value).toFixed(0); }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                // label already contains the wijk name
                                return context[0] ? context[0].label : '';
                            },
                            label: function(context) {
                                const idx = context.dataIndex;
                                const original = keluargaValues[idx] ?? 0;
                                return 'Jumlah: ' + original;
                            }
                        }
                    }
                }
            }
        });

        // keluarga legend removed per request
    }

    // Gender distribution - doughnut chart
    const elGender = document.getElementById('genderChart');
    if (elGender && typeof Chart !== 'undefined') {
        const ctxGender = elGender.getContext ? elGender.getContext('2d') : elGender;
        const chartGender = new Chart(ctxGender, {
            type: 'doughnut',
            data: {
                labels: genderLabels,
                datasets: [{
                    data: genderValues,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(201, 203, 207, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(201, 203, 207, 1)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            // ensure legend labels are white on dark background
                            color: '#ffffff'
                        }
                    }
                }
            }
        });

        // Helper: check whether a segment is visible (handles Chart.js legend toggles)
        function isSegmentVisible(chart, index) {
            try {
                if (typeof chart.getDataVisibility === 'function') return chart.getDataVisibility(index);
            } catch (e) {}
            try {
                const meta = chart.getDatasetMeta && chart.getDatasetMeta(0);
                if (meta && meta.data && meta.data[index] && typeof meta.data[index].hidden !== 'undefined') {
                    return !meta.data[index].hidden;
                }
            } catch (e) {}
            return true;
        }

        // Helper: compute total from chart dataset but only include visible segments
        function computeSumFromChart(chart) {
            try {
                const ds = (chart && chart.data && chart.data.datasets && chart.data.datasets[0]) ? chart.data.datasets[0].data : [];
                return ds.reduce((sum, v, i) => {
                    return sum + (isSegmentVisible(chart, i) ? (Number(v) || 0) : 0);
                }, 0);
            } catch (e) { return 0; }
        }

        // Wrap legend click so center updates when user clicks default legend
        try {
            const legendOpts = chartGender.options && chartGender.options.plugins && chartGender.options.plugins.legend;
            const origLegendOnClick = legendOpts && legendOpts.onClick;
            chartGender.options.plugins.legend.onClick = function (e, legendItem, legend) {
                if (typeof origLegendOnClick === 'function') origLegendOnClick.call(this, e, legendItem, legend);
                // update after the chart has toggled visibility
                setTimeout(() => {
                    const totalNow = computeSumFromChart(chartGender);
                    const center = document.getElementById('genderTotalCenter');
                    if (center) center.innerHTML = `<div class="value">${totalNow}</div>`;
                }, 40);
            };
        } catch (e) {
            // ignore
        }

        // show breakdown badges under the chart (Laki-laki / Perempuan)
            const breakdownEl = document.getElementById('genderBreakdown');
        if (breakdownEl) {
            const totalGender = genderValues.reduce((s, v) => s + (Number(v) || 0), 0);
            // find indexes
            const idxMale = genderLabels.findIndex(l => /laki/i.test(l));
            const idxFemale = genderLabels.findIndex(l => /perempuan|p/i.test(l));
            const maleCount = idxMale >= 0 ? Number(genderValues[idxMale]) || 0 : 0;
            const femaleCount = idxFemale >= 0 ? Number(genderValues[idxFemale]) || 0 : 0;

            const maleColor = 'rgba(75, 192, 192, 0.85)';
            const femaleColor = 'rgba(255, 205, 86, 0.85)';

                // place total in center of doughnut (precise positioning)
                const centerEl = document.getElementById('genderTotalCenter');
                function positionCenter() {
                    if (!centerEl || !elGender) return;
                    const canvasRect = elGender.getBoundingClientRect();
                    const parentRect = elGender.parentElement.getBoundingClientRect();
                    let cx, cy;
                    // Prefer Chart.js chartArea center for precise doughnut center
                    if (typeof chartGender !== 'undefined' && chartGender && chartGender.chartArea && typeof chartGender.chartArea.left !== 'undefined') {
                        const area = chartGender.chartArea;
                        // area coords are relative to canvas top-left
                        cx = canvasRect.left - parentRect.left + (area.left + area.right) / 2;
                        cy = canvasRect.top - parentRect.top + (area.top + area.bottom) / 2;
                    } else {
                        // fallback to canvas center
                        cx = canvasRect.left - parentRect.left + canvasRect.width / 2;
                        cy = canvasRect.top - parentRect.top + canvasRect.height / 2;
                    }
                    centerEl.style.left = cx + 'px';
                    centerEl.style.top = cy + 'px';
                    centerEl.style.transform = 'translate(-50%, -50%)';
                }
                if (centerEl) {
                    centerEl.innerHTML = `<div class="value">${totalGender}</div>`;
                    // Initial positioning after chart render
                    requestAnimationFrame(() => setTimeout(positionCenter, 50));
                    // Reposition on window resize and when canvas size changes
                    window.addEventListener('resize', positionCenter);
                    if (typeof ResizeObserver !== 'undefined') {
                        const ro = new ResizeObserver(positionCenter);
                        ro.observe(elGender);
                        ro.observe(elGender.parentElement);
                    }
                }

            breakdownEl.innerHTML = `
                <div class="d-flex gap-3 justify-content-center w-100 mb-0">
                    <div class="text-center gender-box px-3 py-2" data-gender="male" data-index="${idxMale}">
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <span class="badge rounded-circle me-2" style="background:${maleColor};width:12px;height:12px;display:inline-block;"></span>
                            <div class="gender-label" style="font-size:0.9rem;">Laki-laki</div>
                        </div>
                        <div><span class="fw-bold gender-value" style="font-size:1.05rem;">${maleCount}</span></div>
                    </div>
                    <div class="text-center gender-box px-3 py-2" data-gender="female" data-index="${idxFemale}">
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <span class="badge rounded-circle me-2" style="background:${femaleColor};width:12px;height:12px;display:inline-block;"></span>
                            <div class="gender-label" style="font-size:0.9rem;">Perempuan</div>
                        </div>
                        <div><span class="fw-bold gender-value" style="font-size:1.05rem;">${femaleCount}</span></div>
                    </div>
                </div>`;

            // Keep a numeric copy of original values and a label->value map so toggling can zero/restore values
            const originalGenderValues = genderValues.map(v => Number(v) || 0);
            const originalByLabel = (genderLabels || []).reduce((acc, lbl, i) => { acc[String(lbl)] = originalGenderValues[i] || 0; return acc; }, {});

            // Add click handlers to toggle genders on/off and update center total
            const maleBox = breakdownEl.querySelector('.gender-box[data-gender="male"]');
            const femaleBox = breakdownEl.querySelector('.gender-box[data-gender="female"]');

            let activeMale = true;
            let activeFemale = true;

            function updateGenderDisplay() {
                // Rebuild data by labels to avoid index mismatch issues
                const labels = chartGender.data.labels || genderLabels || [];
                const newData = labels.map((lbl) => {
                    const label = String(lbl);
                    const val = originalByLabel.hasOwnProperty(label) ? originalByLabel[label] : 0;
                    // if label matches male/female index, apply active flags
                    if (idxMale >= 0 && label === String(genderLabels[idxMale]) && !activeMale) return 0;
                    if (idxFemale >= 0 && label === String(genderLabels[idxFemale]) && !activeFemale) return 0;
                    return val;
                });

                // Assign rebuilt array and update chart
                chartGender.data.datasets[0].data = newData;
                chartGender.update();

                const totalNow = newData.reduce((s, v) => s + (Number(v) || 0), 0);
                if (centerEl) centerEl.innerHTML = `<div class="value">${totalNow}</div>`;

                if (maleBox) maleBox.classList.toggle('disabled', !activeMale);
                if (femaleBox) femaleBox.classList.toggle('disabled', !activeFemale);
            }

            if (maleBox) {
                if (idxMale < 0) maleBox.style.display = 'none';
                maleBox.addEventListener('click', function () {
                    activeMale = !activeMale;
                    updateGenderDisplay();
                });
            }
            if (femaleBox) {
                if (idxFemale < 0) femaleBox.style.display = 'none';
                femaleBox.addEventListener('click', function () {
                    activeFemale = !activeFemale;
                    updateGenderDisplay();
                });
            }
        }
    }
});
</script>

@endpush

@endsection