<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LabManager Terminal | LabSync</title>
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
            --amber: #f5a623;
            --red: #ff4d5a;
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

        /* noise grain overlay from welcome page */
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
            padding: 4rem 2rem;
        }

        header {
            border-bottom: 1px solid var(--border);
            padding-bottom: 2rem;
            margin-bottom: 3rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .eyebrow {
            font-family: var(--font-mono);
            font-size: .7rem;
            letter-spacing: .18em;
            color: var(--amber);
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

        section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
        }

        h2 {
            font-family: var(--font-head);
            font-size: 1.25rem;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        h2::before {
            content: '';
            width: 4px;
            height: 1.2rem;
            background: var(--amber);
            display: inline-block;
        }

        /* ── Terminal Input ── */
        .terminal-input-group {
            display: flex;
            border: 1px solid var(--border);
            background: var(--bg);
            border-radius: 4px;
            overflow: hidden;
            transition: border-color 0.3s;
        }

        .terminal-input-group:focus-within {
            border-color: var(--amber);
        }

        .field-core {
            flex: 1;
            padding: 12px 16px;
        }

        .field-core label {
            display: block;
            font-family: var(--font-mono);
            font-size: 0.65rem;
            color: var(--muted);
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .field-core input {
            width: 100%;
            background: transparent;
            border: none;
            color: var(--text);
            font-family: var(--font-mono);
            font-size: 1rem;
            outline: none;
        }

        .btn-terminate {
            background: var(--border);
            border: none;
            border-left: 1px solid var(--border);
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 30px;
            cursor: pointer;
            transition: 0.2s;
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 0.8rem;
        }

        .btn-terminate:hover {
            background: var(--red);
            color: #fff;
        }

        /* ── Grid Layout ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .full-width {
            grid-column: span 2;
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
            outline: none;
        }

        .standard-input:focus {
            border-color: var(--amber);
        }

        /* ── Styled Status Radios ── */
        .status-container {
            display: flex;
            gap: 20px;
            margin-bottom: 1.5rem;
        }

        .status-option {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            cursor: pointer;
        }

        .status-option input {
            accent-color: var(--amber);
        }

        .btn-submit {
            background: var(--amber);
            color: #000;
            border: none;
            padding: 1.2rem;
            font-family: var(--font-mono);
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 2rem;
            transition: 0.2s;
        }

        .btn-submit:hover {
            filter: brightness(1.1);
            transform: translateY(-2px);
        }

        .alert-success {
            background: rgba(245, 166, 35, 0.1);
            border: 1px solid var(--amber);
            color: var(--amber);
            padding: 1rem;
            margin-bottom: 2rem;
            font-family: var(--font-mono);
            font-size: 0.85rem;
            border-radius: 4px;
        }

        /* ── Utilization Heatmap (simplified & aligned) ── */
        .heatmap-container { margin-top: 1rem; display:flex; flex-direction:column; gap:8px; }
        .heatmap-wrap { overflow-x:auto; }
        .heatmap-grid {
            display: grid;
            grid-template-columns: 96px repeat(24, 28px);
            gap: 6px;
            align-items: center;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            justify-content: start;
        }
        .heatmap-label { color: var(--muted); text-transform: uppercase; padding: 4px 8px; width:88px; box-sizing:border-box; }
        .heatmap-cell {
            width:28px; height:28px; border-radius:3px; background: rgba(255,255,255,0.02);
            display:flex; align-items:center; justify-content:center; color: transparent; position:relative;
        }
        .heatmap-cell .hint { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); color: var(--text); font-size:0.65rem; opacity:0; transition: opacity 0.12s; white-space:nowrap; }
        .heatmap-cell:hover .hint { opacity: 1; }
        .heatmap-legend { display:flex; gap:8px; align-items:center; margin-top:6px; }
        .legend-swatch { width:120px; height:12px; border-radius:6px; background: linear-gradient(90deg, rgba(10,10,10,0.05), rgba(200,240,74,0.95)); box-shadow: inset 0 0 8px rgba(0,0,0,0.6); }
        .legend-label { color: var(--muted); font-family: var(--font-mono); font-size:0.8rem; }
    </style>
</head>

<body>

    <div class="shell">
        <header>
            <div>
                <p class="eyebrow">// Inventory Logistics</p>
                <h1>Lab <span>Manager</span></h1>
            </div>
            <x-nav-actions />
        </header>

        <section>
            <h2>Decommission Unit</h2>
            <form method="POST" action="{{ route('LabM.equipment.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="terminal-input-group">
                    <div class="field-core">
                        <label>Target Equipment UID</label>
                        <input type="text" name="equipment_id" placeholder="EQP_01" required>
                    </div>
                    <button type="submit" class="btn-terminate">
                        <span>REMOVE</span>
                        <svg width="14" viewBox="0 0 448 512" fill="currentColor">
                            <path
                                d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>
        </section>
        <!-- heatmap placeholder moved below to keep sections separate -->

        <section>
            <h2>Catalog New Asset</h2>

            @if (session('success'))
                <div class="alert-success">
                    > ASSET_SYNC_COMPLETE: {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('LabM.equipment.store') }}">
                @csrf

                <div class="form-group full-width" style="margin-bottom: 1.5rem;">
                    <label>Operational Status</label>
                    <div class="status-container">
                        <label class="status-option"><input type="radio" name="equipment_status" value="Idle"
                                required> Idle</label>
                        <label class="status-option"><input type="radio" name="equipment_status" value="Active"
                                required> Active</label>
                        <label class="status-option"><input type="radio" name="equipment_status" value="Maintenance"
                                required> Maintenance</label>
                        <label class="status-option"><input type="radio" name="equipment_status" value="Locked"
                                required> Locked</label>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Asset Identity</label>
                        <input type="text" name="equipment_name" class="standard-input"
                            placeholder="e.g., Spectrometer X-10" required>
                    </div>

                    <div class="form-group">
                        <label>Hourly Credit Rate ($)</label>
                        <input type="number" step="0.01" name="hourly_rate" class="standard-input"
                            placeholder="200.50" required>
                    </div>

                    <div class="form-group">
                        <label>Required Clearance Level</label>
                        <input type="number" name="required_clearance" class="standard-input" min="0"
                            max="3" required>
                    </div>

                    <div class="form-group">
                        <label>Category Identifier</label>
                        <input type="number" name="category_id" class="standard-input" placeholder="10" required>
                    </div>

                    <div class="form-group">
                        <label>Sector / Location Code</label>
                        <input type="text" name="location_code" class="standard-input" placeholder="B-104" required>
                    </div>

                    <div class="form-group">
                        <label>Calibration Threshold (Hrs)</label>
                        <input type="number" step="0.1" name="calibration_threshold" class="standard-input"
                            placeholder="500" required>
                    </div>

                    <div class="form-group">
                        <label>Cooldown Buffer (Min)</label>
                        <input type="number" name="cooldown_buffer" class="standard-input" placeholder="15" required>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="standard-input" placeholder="2" min="1"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Initialize Asset Protocol</button>
            </form>
        </section>
        <section>
            <h2>Utilization Heatmap</h2>
            <div class="heatmap-container">
                <div class="heatmap-wrap">
                    <div id="heatmap" class="heatmap-grid">
                        <!-- JS will render: one label column + 24 fixed cells per row -->
                    </div>
                </div>

                <div class="heatmap-legend">
                    <div class="legend-swatch" aria-hidden="true"></div>
                    <div class="legend-label">Low</div>
                    <div style="flex:1"></div>
                    <div class="legend-label">High</div>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Fetch utilization data and render the heatmap grid
        (async function renderHeatmap(){
            try {
                const resp = await fetch('/labmanager/heatmap');
                if (!resp.ok) return;
                const data = await resp.json();
                const container = document.getElementById('heatmap');
                container.innerHTML = '';

                // simplified grid: label column + 24 fixed cells per day (no hour header)
                data.days.forEach((day, dIdx) => {
                    const label = document.createElement('div');
                    label.className = 'heatmap-label';
                    label.textContent = day;
                    container.appendChild(label);

                    for (let h = 0; h < 24; h++) {
                        const cell = document.createElement('div');
                        cell.className = 'heatmap-cell';
                        const percent = (data.percents[dIdx] && data.percents[dIdx][h]) ? data.percents[dIdx][h] : 0;
                        const count = (data.counts[dIdx] && data.counts[dIdx][h]) ? data.counts[dIdx][h] : 0;

                        // convert percent to a color intensity (use accent color with alpha)
                        const alpha = Math.min(0.95, Math.max(0.03, percent / 100));
                        cell.style.background = `rgba(200,240,74, ${alpha})`;

                        const hint = document.createElement('div');
                        hint.className = 'hint';
                        hint.textContent = `${Math.round(percent)}% (${count})`;
                        cell.appendChild(hint);

                        cell.title = `${day} ${h}:00 — ${Math.round(percent)}% utilization (${count} sessions)`;
                        container.appendChild(cell);
                    }
                });

            } catch (err) {
                console.error('Heatmap load failed', err);
            }
        })();
    </script>

</body>

</html>
