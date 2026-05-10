<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PI Terminal | LabSync</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #0b0c0f;
            --surface: #111318;
            --border: #1e2028;
            --text: #e8eaf0;
            --muted: #5a5e72;
            --accent: #c8f04a;
            --blue: #4d9eff;
            --red: #ff4d4d;
            --font-head: 'Syne', sans-serif;
            --font-mono: 'DM Mono', monospace;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-head);
            margin: 0;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .shell {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ── Utility Bar ── */
        .utility-bar {
            display: flex;
            justify-content: space-between;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 1rem 1.5rem;
            border-radius: 4px;
            margin-bottom: 2rem;
            font-family: var(--font-mono);
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-label {
            font-size: 0.6rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 1rem;
            color: var(--accent);
            font-weight: 500;
        }

        /* ── Header ── */
        header {
            border-bottom: 1px solid var(--border);
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .eyebrow {
            font-family: var(--font-mono);
            font-size: .7rem;
            color: var(--blue);
            text-transform: uppercase;
            margin: 0;
        }

        h1 {
            font-size: 3rem;
            font-weight: 800;
            margin: 0.5rem 0;
            text-transform: uppercase;
        }

        h1 span {
            color: var(--muted);
            font-weight: 400;
        }

        /* ── Tabs ── */
        .tab-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .tab-btn {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 0.8rem 1.2rem;
            font-family: var(--font-mono);
            font-size: 0.7rem;
            cursor: pointer;
            text-transform: uppercase;
            transition: 0.2s;
        }

        .tab-btn.active {
            border-color: var(--blue);
            color: var(--text);
            background: rgba(77, 158, 255, 0.05);
        }

        .tab-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--red);
            color: #fff;
            border-radius: 2px;
            font-size: .55rem;
            font-weight: 700;
            padding: .1rem .35rem;
            margin-left: .5rem;
            vertical-align: middle;
            line-height: 1;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Section Card ── */
        section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2.5rem;
        }

        h2 {
            font-size: 1.25rem;
            margin: 0 0 2rem 0;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        h2::before {
            content: '';
            width: 4px;
            height: 1.2rem;
            background: var(--blue);
            flex-shrink: 0;
        }

        /* ── Form ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group label {
            display: block;
            font-family: var(--font-mono);
            font-size: 0.65rem;
            color: var(--muted);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .standard-input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 1rem;
            color: var(--text);
            font-family: var(--font-mono);
        }

        .standard-input:focus {
            border-color: var(--blue);
            outline: none;
        }

        .btn-submit {
            background: var(--blue);
            color: #fff;
            border: none;
            padding: 1.2rem;
            font-family: var(--font-mono);
            font-weight: 700;
            text-transform: uppercase;
            width: 100%;
            margin-top: 2rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-submit:hover {
            filter: brightness(1.1);
        }

        /* ── Reservation Items ── */
        .res-item {
            background: var(--bg);
            border: 1px solid var(--border);
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .res-data {
            font-family: var(--font-mono);
            flex: 1;
            min-width: 0;
        }

        .res-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 4px 0;
        }

        .res-sub {
            font-size: 0.7rem;
            color: var(--muted);
            margin: 4px 0 0 0;
            line-height: 1.6;
        }

        .res-sub span {
            color: var(--text);
        }

        .res-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .res-actions form {
            margin: 0;
        }

        .action-btn {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.6rem 1rem;
            font-family: var(--font-mono);
            font-size: 0.65rem;
            cursor: pointer;
            text-transform: uppercase;
            transition: .15s;
        }

        .action-btn.approve:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .action-btn.reject:hover {
            border-color: var(--red);
            color: var(--red);
        }

        /* ── Publication Items ── */
        .pub-item {
            background: var(--bg);
            border: 1px solid var(--border);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
            font-family: var(--font-mono);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .pub-data {
            flex: 1;
            min-width: 0;
        }

        .pub-doi {
            font-size: .85rem;
            font-weight: 700;
            color: var(--accent);
            margin: 0 0 4px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pub-meta {
            font-size: .68rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.6;
        }

        .pub-meta span {
            color: var(--text);
        }

        /* ── Alerts & Empty ── */
        .alert-success {
            background: rgba(77, 158, 255, .1);
            border: 1px solid var(--blue);
            color: var(--blue);
            padding: 1rem;
            margin-bottom: 2rem;
            font-family: var(--font-mono);
            font-size: .85rem;
        }

        .alert-error {
            background: rgba(255, 77, 77, .1);
            border: 1px solid var(--red);
            color: var(--red);
            padding: 1rem;
            margin-bottom: 2rem;
            font-family: var(--font-mono);
            font-size: .85rem;
        }

        .res-empty {
            font-family: var(--font-mono);
            font-size: .8rem;
            color: var(--muted);
            padding: 2rem;
            text-align: center;
            border: 1px dashed var(--border);
            border-radius: 4px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 2rem 0;
        }

        .grant-row {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            margin-bottom: .75rem;
        }

        .grant-row .form-group {
            margin: 0;
        }

        .btn-remove-grant {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--red);
            width: 2.2rem;
            height: 2.2rem;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 3px;
            transition: .15s;
            flex-shrink: 0;
            margin-bottom: 1px;
            /* optical alignment with inputs */
        }

        .btn-remove-grant:hover {
            background: rgba(255, 77, 77, .08);
            border-color: var(--red);
        }
    </style>
</head>

<body>
    <div class="shell">

        @php $budget = auth()->user()->PiProfile->budget_limit; @endphp

        {{-- ── Utility Bar ── --}}
        <div class="utility-bar">
            <div class="stat-item">
                <span class="stat-label">Pending_Reservations</span>
                <span class="stat-value">{{ $pendingReservations->count() }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Publications_Linked</span>
                <span class="stat-value">{{ $publicationLinks->count() }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Budget</span>
                <span class="stat-value">{{ $budget }}</span>
            </div>
        </div>

        {{-- ── Header ── --}}
        <header>
            <div>
                <p class="eyebrow">// Research Oversight</p>
                <h1>PI <span>Dashboard</span></h1>
            </div>
            <div class="header-nav">
                <x-nav-actions />
            </div>
        </header>

        {{-- ── Flash Messages ── --}}
        @if (session('success'))
            <div class="alert-success">&gt; {{ session('success') }}</div>
        @elseif (session('fail'))
            <div class="alert-error">&gt; {{ session('fail') }}</div>
        @elseif (session('error'))
            <div class="alert-error">&gt; {{ session('error') }}</div>
        @endif

        {{-- ── Tabs ── --}}
        <div class="tab-nav">
            <button class="tab-btn active" onclick="showTab('provision-sec', this)">01_Provisioning</button>
            <button class="tab-btn" onclick="showTab('pending-sec', this)">
                02_Pending_Reservations
                @if ($pendingReservations->count() > 0)
                    <span class="tab-count">{{ $pendingReservations->count() }}</span>
                @endif
            </button>
            <button class="tab-btn" onclick="showTab('publications-sec', this)">03_Publications</button>
            <button class="tab-btn" onclick="showTab('grants-sec', this)">04_Grant_Allocation</button>
        </div>

        {{-- ══ TAB 1: Provision Researcher ══ --}}
        <div id="provision-sec" class="tab-content active">
            <section>
                <h2>Provision Researcher</h2>
                <form method="POST" action="{{ route('pi.researcher.store') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Researcher Name</label>
                            <input type="text" name="user_name" class="standard-input" placeholder="Full Name"
                                value="{{ old('user_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Researcher Email</label>
                            <input type="email" name="user_email" class="standard-input"
                                placeholder="name@labsync.sys" value="{{ old('user_email') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Academic Level</label>
                            <input type="text" name="academic_level" class="standard-input"
                                placeholder="e.g., PhD, Post-Doc" value="{{ old('academic_level') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Clearance Level (0–3)</label>
                            <input type="number" name="clearance_level" class="standard-input" min="0"
                                max="3" value="{{ old('clearance_level', 1) }}">
                        </div>
                        <div class="form-group">
                            <label>Initial Password</label>
                            <input type="password" name="user_pass" class="standard-input" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label>Authorization Expiry</label>
                            <input type="date" name="expiry_date" class="standard-input" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Initialize Researcher Credentials</button>
                </form>
            </section>
        </div>

        {{-- ══ TAB 2: Pending Reservations ══ --}}
        <div id="pending-sec" class="tab-content">
            <section>
                <h2>Pending Approvals</h2>

                @forelse ($pendingReservations as $reservation)
                    <div class="res-item">
                        <div class="res-data">
                            <p class="res-label">
                                {{ optional($reservation->equipment)->name ?? 'Unknown Equipment' }}
                            </p>
                            <p class="res-label">Total Cost:
                                {{ app('App\Services\ReservationService')->calculateCost($reservation) }}
                            </p>
                            <p class="res-sub">
                                Researcher: <span>{{ optional($reservation->user)->name ?? '—' }}</span><br>
                                From:
                                <span>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d M Y, H:i') }}</span>
                                &rarr;
                                <span>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d M Y, H:i') }}</span><br>
                                Submitted: <span>{{ $reservation->created_at->diffForHumans() }}</span>
                            </p>
                        </div>

                        <div class="res-actions">
                            <form method="POST" action="{{ route('pi.reservation.approve', $reservation->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn approve">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('pi.reservation.reject', $reservation->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn reject">Reject</button>
                            </form>
                        </div>
                    </div>

                @empty
                    <div class="res-empty">// NO_PENDING_RESERVATIONS — queue is clear</div>
                @endforelse

            </section>
        </div>

        {{-- ══ TAB 3: Publications ══ --}}
        <div id="publications-sec" class="tab-content">
            <section>
                <h2>Link Publication</h2>

                @php
                    $PIid = auth()->id();
                @endphp

                <form method="POST" action="{{ route('pi.publication.store') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label>DOI</label>
                            <input type="text" name="doi" class="standard-input" placeholder="10.1000/xyz123"
                                value="{{ old('doi') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Equipment Used</label>
                            <select name="equipment_id" class="standard-input" required>
                                <option value="" disabled selected>— Select equipment —</option>
                                @foreach ($usedEquipments as $eq)
                                    <option value="{{ $eq->id }}"
                                        {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>
                                        {{ $eq->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="pi_id" value="{{ $PIid }}">
                    <button type="submit" class="btn-submit">Link Publication</button>
                </form>

                {{-- ── Existing Links ── --}}
                @if ($publicationLinks->isNotEmpty())
                    <hr class="section-divider">
                    <h2>Linked Publications</h2>

                    @foreach ($publicationLinks as $link)
                        <div class="pub-item">
                            <div class="pub-data">
                                <p class="pub-doi">{{ $link->doi }}</p>
                                <p class="pub-meta">
                                    Equipment: <span>{{ optional($link->equipment)->name ?? '—' }}</span>
                                    &nbsp;&middot;&nbsp;
                                    Linked: <span>{{ $link->created_at->format('d M Y') }}</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif

            </section>
        </div>

        {{-- ══ TAB 4: Grant Allocation ══
     Controller must pass:
       $unallocatedTransactions — Transaction::whereDoesntHave('transactionGrants')
                                    ->where('user_id', ... pi's researchers ...)
                                    ->with('session.equipment', 'session.user')
                                    ->get();
       $piGrants               — Grant::where('pi_id', auth()->user()->piProfile->user_id)->get();
--}}
        <div id="grants-sec" class="tab-content">
            <section>
                <h2>Grant Allocation</h2>

                @forelse ($unallocatedTransactions as $transaction)
                    <div class="res-item" style="flex-direction: column; align-items: stretch; gap: 1.5rem;">

                        {{-- ── Transaction Header ── --}}
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div class="res-data">
                                <p class="res-label">
                                    {{ optional($transaction->equipmentSession->equipment)->name ?? '—' }}
                                </p>
                                <p class="res-sub">
                                    Researcher:
                                    <span>{{ optional($transaction->equipmentSession->user)->name ?? '—' }}</span><br>
                                    Session ended:
                                    <span>{{ $transaction->created_at->format('d M Y, H:i') }}</span><br>
                                    Total Cost: <span>${{ number_format($transaction->total_cost, 2) }}</span>
                                </p>
                            </div>
                            <span
                                style="font-family:var(--font-mono); font-size:.65rem; color:var(--muted); white-space:nowrap;">
                                TXN-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        {{-- ── Split Allocation Form ── --}}
                        <form method="POST" action="{{ route('pi.transaction.allocate', $transaction->id) }}"
                            class="allocation-form" data-cost="{{ $transaction->total_cost }}">
                            @csrf

                            {{-- Dynamic grant rows — JS adds/removes these --}}
                            <div class="grant-rows" id="grant-rows-{{ $transaction->id }}">

                                {{-- Row 1 (always shown) --}}
                                <div class="grant-row">
                                    <div class="form-group" style="flex:2;">
                                        <label>Grant</label>
                                        <select name="allocations[0][grant_id]" class="standard-input" required>
                                            <option value="" disabled selected>— Select grant —</option>
                                            @foreach ($piGrants as $grant)
                                                <option value="{{ $grant->id }}">
                                                    {{ $grant->name }} (Balance:
                                                    ${{ number_format($grant->balance, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group" style="flex:1;">
                                        <label>Percentage (%)</label>
                                        <input type="number" name="allocations[0][percentage]"
                                            class="standard-input pct-input" min="1" max="100"
                                            placeholder="100" required>
                                    </div>
                                    <div style="padding-top:1.6rem; flex-shrink:0;">
                                        {{-- placeholder to keep layout aligned on first row --}}
                                        <span style="display:inline-block; width:2.2rem;"></span>
                                    </div>
                                </div>

                            </div>

                            {{-- ── Summary row ── --}}
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-top:1rem;">
                                <button type="button" class="action-btn"
                                    onclick="addGrantRow(this, {{ $transaction->id }}, {{ $transaction->total_cost }})">
                                    + Add Grant
                                </button>

                                <div style="font-family:var(--font-mono); font-size:.72rem; text-align:right;">
                                    <span style="color:var(--muted);">Total allocated: </span>
                                    <span class="pct-total" id="pct-total-{{ $transaction->id }}"
                                        style="color:var(--accent);">0%</span>
                                    &nbsp;/&nbsp;
                                    <span style="color:var(--muted);">Remaining: </span>
                                    <span class="pct-remaining" id="pct-remaining-{{ $transaction->id }}"
                                        style="color:var(--text);">100%</span>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit" style="margin-top:1.2rem;">
                                Confirm Allocation
                            </button>
                        </form>

                    </div>
                @empty
                    <div class="res-empty">// NO_PENDING_ALLOCATIONS — all transactions are allocated</div>
                @endforelse

            </section>
        </div>
    </div>{{-- /shell --}}

    <script>
        function showTab(tabId, btn) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            btn.classList.add('active');
        }

        const tab = new URLSearchParams(window.location.search).get('tab');
        if (tab === 'pending') document.querySelectorAll('.tab-btn')[1]?.click();
        if (tab === 'publications') document.querySelectorAll('.tab-btn')[2]?.click();
        if (tab === 'grants') document.querySelectorAll('.tab-btn')[3]?.click();
        // ── Grant row counter per transaction ──
        const rowCounters = {};

        function addGrantRow(btn, txnId, totalCost) {
            if (!rowCounters[txnId]) rowCounters[txnId] = 1;
            const idx = ++rowCounters[txnId];

            const container = document.getElementById(`grant-rows-${txnId}`);

            const row = document.createElement('div');
            row.className = 'grant-row';
            row.innerHTML = `
            <div class="form-group" style="flex:2;">
                <label>Grant</label>
                <select name="allocations[${idx}][grant_id]" class="standard-input" required>
                    <option value="" disabled selected>— Select grant —</option>
                    @foreach ($piGrants as $grant)
                        <option value="{{ $grant->id }}">
                            {{ $grant->name }} (Balance: ${{ number_format($grant->balance, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Percentage (%)</label>
                <input type="number" name="allocations[${idx}][percentage]"
                       class="standard-input pct-input"
                       min="1" max="100" placeholder="0" required>
            </div>
            <button type="button" class="btn-remove-grant"
                    onclick="removeGrantRow(this, ${txnId})">×</button>
        `;

            container.appendChild(row);
            bindPctInputs(txnId);
        }

        function removeGrantRow(btn, txnId) {
            btn.closest('.grant-row').remove();
            updatePctSummary(txnId);
        }

        // ── Live percentage summary ──
        function updatePctSummary(txnId) {
            const container = document.getElementById(`grant-rows-${txnId}`);
            const inputs = container.querySelectorAll('.pct-input');
            const total = Array.from(inputs).reduce((sum, el) => sum + (parseFloat(el.value) || 0), 0);
            const remaining = 100 - total;

            const totalEl = document.getElementById(`pct-total-${txnId}`);
            const remainingEl = document.getElementById(`pct-remaining-${txnId}`);

            totalEl.textContent = total.toFixed(0) + '%';
            remainingEl.textContent = remaining.toFixed(0) + '%';

            // Color feedback
            totalEl.style.color = total === 100 ? 'var(--accent)' : total > 100 ? 'var(--red)' : 'var(--muted)';
            remainingEl.style.color = remaining === 0 ? 'var(--accent)' : remaining < 0 ? 'var(--red)' : 'var(--text)';
        }

        function bindPctInputs(txnId) {
            const container = document.getElementById(`grant-rows-${txnId}`);
            container.querySelectorAll('.pct-input').forEach(input => {
                input.removeEventListener('input', input._handler);
                input._handler = () => updatePctSummary(txnId);
                input.addEventListener('input', input._handler);
            });
        }

        // Init all on page load
        document.querySelectorAll('[id^="grant-rows-"]').forEach(container => {
            const txnId = container.id.replace('grant-rows-', '');
            bindPctInputs(txnId);
        });
    </script>
</body>

</html>
