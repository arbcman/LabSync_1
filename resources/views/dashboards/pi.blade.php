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
            position: relative;
        }

        .tab-btn.active {
            border-color: var(--blue);
            color: var(--text);
            background: rgba(77, 158, 255, 0.05);
        }

        /* pending count badge on tab */
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

        /* ── UI Components ── */
        section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2.5rem;
        }

        h2 {
            font-size: 1.25rem;
            margin-bottom: 2rem;
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
        }

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
            box-sizing: border-box;
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

        /* ── Reservations List ── */
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
            margin: 0;
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

        /* empty state */
        .res-empty {
            font-family: var(--font-mono);
            font-size: .8rem;
            color: var(--muted);
            padding: 2rem;
            text-align: center;
            border: 1px dashed var(--border);
            border-radius: 4px;
        }

        /* alerts */
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
    </style>
</head>

<body>
    <div class="shell">
        @php
            $budget = auth()->user()->PiProfile->budget_limit;
        @endphp
        {{-- Utility bar --}}
        <div class="utility-bar">
            <div class="stat-item">
                <span class="stat-label">Pending_Reservations</span>
                <span class="stat-value">{{ $pendingReservations->count() }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Budget</span>
                <span class="stat-value">{{ $budget }}</span>
            </div>
        </div>

        <header>
            <div>
                <p class="eyebrow">// Research Oversight</p>
                <h1>PI <span>Dashboard</span></h1>
            </div>
            <div class="header-nav">
                <x-nav-actions />
            </div>
        </header>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert-success">&gt; {{ session('success') }}</div>
        @elseif (session('fail'))
            <div class="alert-error">&gt; {{ session('fail') }}</div>
        @elseif (session('error'))
            <div class="alert-error">&gt; {{ session('error') }}</div>
        @endif

        {{-- Tabs --}}
        <div class="tab-nav">
            <button class="tab-btn active" onclick="showTab('provision-sec', this)">01_Provisioning</button>
            <button class="tab-btn" onclick="showTab('pending-sec', this)">
                02_Pending_Reservations
                @if ($pendingReservations->count() > 0)
                    <span class="tab-count">{{ $pendingReservations->count() }}</span>
                @endif
            </button>
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
                            <p class="res-label"> Total Cost:
                                {{ app('App\Services\ReservationService')->calculateCost($reservation) }}</p>
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
                            {{-- Approve --}}
                            <form method="POST" action="{{ route('pi.reservation.approve', $reservation->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn approve">Approve</button>
                            </form>

                            {{-- Reject --}}
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

    </div>

    <script>
        function showTab(tabId, btn) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            btn.classList.add('active');
        }

        // Auto-open pending tab if redirected with ?tab=pending
        if (new URLSearchParams(window.location.search).get('tab') === 'pending') {
            document.querySelectorAll('.tab-btn')[1]?.click();
        }
    </script>
</body>

</html>
