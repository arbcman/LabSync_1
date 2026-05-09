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
    </script>
</body>

</html>
