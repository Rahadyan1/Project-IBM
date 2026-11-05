<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TestForge — Test Case Generator</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg: #0f1117;
      --surface: #1a1d27;
      --surface2: #222536;
      --border: #2e3250;
      --accent: #6c63ff;
      --accent2: #00d2ff;
      --green: #00e676;
      --red: #ff5370;
      --yellow: #ffd740;
      --text: #e8eaf6;
      --muted: #8892b0;
      --radius: 10px;
    }

    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
    }

    /* ── HEADER ── */
    header {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 60px;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .logo { display: flex; align-items: center; gap: 10px; font-size: 1.25rem; font-weight: 700; }
    .logo-icon {
      width: 32px; height: 32px; border-radius: 8px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem;
    }
    .logo span { background: linear-gradient(90deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .header-right { display: flex; align-items: center; gap: 12px; }
    .badge { background: var(--accent); color: #fff; font-size: 0.65rem; padding: 2px 8px; border-radius: 20px; font-weight: 700; letter-spacing: .5px; }

    /* ── LAYOUT ── */
    .layout {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 0;
      min-height: calc(100vh - 60px);
    }

    /* ── SIDEBAR ── */
    .sidebar {
      background: var(--surface);
      border-right: 1px solid var(--border);
      padding: 24px 20px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      overflow-y: auto;
    }

    .section-title {
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 8px;
    }

    label { font-size: 0.82rem; color: var(--muted); display: block; margin-bottom: 4px; }

    input[type=text], input[type=number], textarea, select {
      width: 100%;
      background: var(--surface2);
      border: 1px solid var(--border);
      border-radius: 6px;
      color: var(--text);
      font-size: 0.85rem;
      padding: 8px 10px;
      outline: none;
      transition: border-color .2s;
      font-family: inherit;
    }
    input[type=text]:focus, input[type=number]:focus, textarea:focus, select:focus {
      border-color: var(--accent);
    }
    textarea { resize: vertical; min-height: 80px; }
    select option { background: var(--surface2); }

    .field { margin-bottom: 12px; }

    /* TAG INPUT */
    .tag-container {
      display: flex; flex-wrap: wrap; gap: 6px;
      background: var(--surface2); border: 1px solid var(--border);
      border-radius: 6px; padding: 6px 8px; min-height: 38px;
      cursor: text;
    }
    .tag-container:focus-within { border-color: var(--accent); }
    .tag {
      background: var(--accent); color: #fff;
      border-radius: 4px; padding: 2px 8px;
      font-size: 0.75rem; display: flex; align-items: center; gap: 4px;
    }
    .tag .remove { cursor: pointer; opacity: .7; font-size: .9em; }
    .tag .remove:hover { opacity: 1; }
    .tag-input { border: none; background: transparent; color: var(--text); outline: none; font-size: 0.82rem; min-width: 80px; flex: 1; }

    /* TOGGLE */
    .toggle-row { display: flex; justify-content: space-between; align-items: center; }
    .toggle { position: relative; display: inline-block; width: 38px; height: 20px; }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .slider {
      position: absolute; inset: 0; background: var(--border);
      border-radius: 20px; cursor: pointer; transition: .2s;
    }
    .slider::before {
      content: ''; position: absolute;
      width: 14px; height: 14px; left: 3px; top: 3px;
      background: #fff; border-radius: 50%; transition: .2s;
    }
    .toggle input:checked + .slider { background: var(--accent); }
    .toggle input:checked + .slider::before { transform: translateX(18px); }

    /* BUTTONS */
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      padding: 9px 18px; border-radius: 6px; border: none; cursor: pointer;
      font-size: 0.85rem; font-weight: 600; transition: .15s; font-family: inherit;
    }
    .btn-primary { background: var(--accent); color: #fff; }
    .btn-primary:hover { background: #7c75ff; }
    .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
    .btn-secondary:hover { border-color: var(--accent); color: var(--accent); }
    .btn-danger { background: transparent; color: var(--red); border: 1px solid var(--red); }
    .btn-danger:hover { background: var(--red); color: #fff; }
    .btn-green { background: var(--green); color: #000; }
    .btn-green:hover { opacity: .85; }
    .btn-sm { padding: 5px 12px; font-size: 0.78rem; }
    .btn-full { width: 100%; }

    /* ── MAIN AREA ── */
    .main { padding: 28px 32px; overflow-y: auto; display: flex; flex-direction: column; gap: 24px; }

    /* STATS BAR */
    .stats-bar {
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;
    }
    .stat-card {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 16px 18px;
    }
    .stat-label { font-size: 0.72rem; color: var(--muted); text-transform: uppercase; letter-spacing: .8px; }
    .stat-value { font-size: 1.6rem; font-weight: 700; margin-top: 4px; }
    .stat-value.green { color: var(--green); }
    .stat-value.red { color: var(--red); }
    .stat-value.yellow { color: var(--yellow); }
    .stat-value.accent { color: var(--accent); }

    /* TOOLBAR */
    .toolbar {
      display: flex; align-items: center; justify-content: space-between;
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 12px 16px;
    }
    .toolbar-left { display: flex; align-items: center; gap: 8px; }
    .toolbar-right { display: flex; align-items: center; gap: 8px; }
    .search-box {
      display: flex; align-items: center; gap: 6px;
      background: var(--surface2); border: 1px solid var(--border);
      border-radius: 6px; padding: 6px 10px;
    }
    .search-box input { border: none; background: transparent; color: var(--text); font-size: 0.82rem; outline: none; width: 180px; }
    .search-box svg { color: var(--muted); flex-shrink: 0; }

    /* FILTER CHIPS */
    .filter-chips { display: flex; gap: 6px; flex-wrap: wrap; }
    .chip {
      padding: 4px 12px; border-radius: 20px; font-size: 0.75rem;
      cursor: pointer; border: 1px solid var(--border); background: transparent; color: var(--muted);
      transition: .15s; font-family: inherit;
    }
    .chip.active { background: var(--accent); color: #fff; border-color: var(--accent); }
    .chip:hover:not(.active) { border-color: var(--accent); color: var(--accent); }

    /* TABLE */
    .table-wrap {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--surface2); }
    th {
      padding: 11px 14px; text-align: left;
      font-size: 0.72rem; text-transform: uppercase; letter-spacing: .8px;
      color: var(--muted); font-weight: 600; border-bottom: 1px solid var(--border);
      cursor: pointer; user-select: none;
    }
    th:hover { color: var(--text); }
    td { padding: 11px 14px; font-size: 0.83rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tbody tr { transition: background .12s; }
    tbody tr:hover { background: rgba(108,99,255,.06); }
    tbody tr.selected { background: rgba(108,99,255,.12); }

    .tc-id { font-family: monospace; color: var(--accent2); font-size: 0.8rem; }
    .tc-title { font-weight: 500; max-width: 220px; }
    .tc-title small { display: block; color: var(--muted); font-weight: 400; font-size: 0.75rem; margin-top: 1px; }
    .priority-badge {
      display: inline-flex; align-items: center; padding: 2px 9px;
      border-radius: 20px; font-size: 0.72rem; font-weight: 700; letter-spacing: .4px;
    }
    .p-critical { background: rgba(255,83,112,.15); color: var(--red); }
    .p-high { background: rgba(255,171,64,.15); color: #ffab40; }
    .p-medium { background: rgba(255,215,64,.15); color: var(--yellow); }
    .p-low { background: rgba(0,230,118,.15); color: var(--green); }

    .status-dot {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 0.8rem;
    }
    .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .dot-pass { background: var(--green); }
    .dot-fail { background: var(--red); }
    .dot-skip { background: var(--yellow); }
    .dot-pending { background: var(--muted); }

    .action-btns { display: flex; gap: 6px; }
    .icon-btn {
      background: transparent; border: 1px solid var(--border);
      border-radius: 5px; padding: 5px 7px; cursor: pointer; color: var(--muted);
      font-size: 0.8rem; transition: .15s;
    }
    .icon-btn:hover { border-color: var(--accent); color: var(--accent); }
    .icon-btn.del:hover { border-color: var(--red); color: var(--red); }

    /* MODAL */
    .overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,.6); backdrop-filter: blur(3px);
      z-index: 200; align-items: center; justify-content: center;
    }
    .overlay.active { display: flex; }
    .modal {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: 14px; padding: 28px 30px; width: 540px; max-width: 95vw;
      max-height: 90vh; overflow-y: auto;
    }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .modal-header h2 { font-size: 1.05rem; font-weight: 700; }
    .close-btn { background: none; border: none; color: var(--muted); cursor: pointer; font-size: 1.3rem; padding: 0 4px; }
    .close-btn:hover { color: var(--text); }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }

    /* STEP EDITOR */
    .steps-list { display: flex; flex-direction: column; gap: 6px; margin-bottom: 8px; }
    .step-row {
      display: flex; align-items: center; gap: 8px;
      background: var(--surface2); border: 1px solid var(--border);
      border-radius: 6px; padding: 8px 10px;
    }
    .step-num {
      width: 22px; height: 22px; background: var(--accent); border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.72rem; font-weight: 700; flex-shrink: 0;
    }
    .step-input { flex: 1; background: transparent; border: none; color: var(--text); font-size: 0.83rem; outline: none; }
    .step-del { background: none; border: none; color: var(--muted); cursor: pointer; font-size: 0.9rem; padding: 2px; }
    .step-del:hover { color: var(--red); }

    /* EMPTY STATE */
    .empty-state {
      text-align: center; padding: 60px 20px; color: var(--muted);
    }
    .empty-icon { font-size: 3rem; margin-bottom: 12px; opacity: .4; }
    .empty-state p { font-size: 0.9rem; }

    /* EXPORT MENU */
    .dropdown { position: relative; display: inline-block; }
    .dropdown-menu {
      display: none; position: absolute; right: 0; top: calc(100% + 6px);
      background: var(--surface); border: 1px solid var(--border);
      border-radius: 8px; overflow: hidden; min-width: 140px; z-index: 50;
    }
    .dropdown.open .dropdown-menu { display: block; }
    .dropdown-item {
      display: flex; align-items: center; gap: 8px;
      padding: 9px 14px; font-size: 0.82rem; cursor: pointer;
      transition: .12s; color: var(--text);
    }
    .dropdown-item:hover { background: rgba(108,99,255,.12); color: var(--accent); }

    /* TOAST */
    #toast {
      position: fixed; bottom: 24px; right: 24px;
      background: var(--surface2); border: 1px solid var(--border);
      border-radius: 8px; padding: 12px 18px; font-size: 0.85rem;
      display: flex; align-items: center; gap: 10px;
      transform: translateY(80px); opacity: 0;
      transition: .3s; z-index: 300; max-width: 320px;
    }
    #toast.show { transform: translateY(0); opacity: 1; }
    #toast.success .ti { color: var(--green); }
    #toast.error .ti { color: var(--red); }

    /* PROGRESS BAR */
    .progress-bar-wrap { background: var(--border); border-radius: 20px; height: 6px; overflow: hidden; margin-top: 6px; }
    .progress-bar { height: 100%; border-radius: 20px; background: linear-gradient(90deg, var(--accent), var(--accent2)); transition: width .4s; }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .layout { grid-template-columns: 1fr; }
      .sidebar { border-right: none; border-bottom: 1px solid var(--border); }
      .stats-bar { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 600px) {
      .stats-bar { grid-template-columns: 1fr 1fr; }
      .main { padding: 16px; }
      .modal { padding: 20px 16px; }
    }
  </style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="logo">
    <div class="logo-icon">⚡</div>
    <span>TestForge</span>
  </div>
  <div class="header-right">
    <span class="badge">v1.0</span>
    <button class="btn btn-primary btn-sm" onclick="openModal('new')">＋ New Test Case</button>
  </div>
</header>

<div class="layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div>
      <div class="section-title">Project Info</div>
      <div class="field">
        <label>Project Name</label>
        <input type="text" id="projectName" placeholder="e.g. E-Commerce App" value="My Project" oninput="updateProjectName()"/>
      </div>
      <div class="field">
        <label>Version / Sprint</label>
        <input type="text" id="projectVersion" placeholder="e.g. v2.1 / Sprint 5" value="v1.0"/>
      </div>
      <div class="field">
        <label>Tester Name</label>
        <input type="text" id="testerName" placeholder="Your name" value="QA Engineer"/>
      </div>
    </div>

    <div>
      <div class="section-title">Quick Generate</div>
      <div class="field">
        <label>Feature / Module Name</label>
        <input type="text" id="qgFeature" placeholder="e.g. User Login"/>
      </div>
      <div class="field">
        <label>Type</label>
        <select id="qgType">
          <option value="functional">Functional</option>
          <option value="integration">Integration</option>
          <option value="regression">Regression</option>
          <option value="smoke">Smoke</option>
          <option value="performance">Performance</option>
          <option value="security">Security</option>
          <option value="ui">UI / UX</option>
          <option value="api">API</option>
        </select>
      </div>
      <div class="field">
        <label>Priority</label>
        <select id="qgPriority">
          <option value="critical">Critical</option>
          <option value="high">High</option>
          <option value="medium" selected>Medium</option>
          <option value="low">Low</option>
        </select>
      </div>
      <div class="field">
        <label>Count to Generate</label>
        <input type="number" id="qgCount" min="1" max="20" value="5"/>
      </div>
      <button class="btn btn-primary btn-full" onclick="quickGenerate()">⚡ Auto-Generate</button>
    </div>

    <div>
      <div class="section-title">Filters</div>
      <div class="field">
        <label>Status</label>
        <select id="filterStatus" onchange="renderTable()">
          <option value="all">All Statuses</option>
          <option value="pending">Pending</option>
          <option value="pass">Pass</option>
          <option value="fail">Fail</option>
          <option value="skip">Skip</option>
        </select>
      </div>
      <div class="field">
        <label>Priority</label>
        <select id="filterPriority" onchange="renderTable()">
          <option value="all">All Priorities</option>
          <option value="critical">Critical</option>
          <option value="high">High</option>
          <option value="medium">Medium</option>
          <option value="low">Low</option>
        </select>
      </div>
      <div class="field">
        <label>Type</label>
        <select id="filterType" onchange="renderTable()">
          <option value="all">All Types</option>
          <option value="functional">Functional</option>
          <option value="integration">Integration</option>
          <option value="regression">Regression</option>
          <option value="smoke">Smoke</option>
          <option value="performance">Performance</option>
          <option value="security">Security</option>
          <option value="ui">UI / UX</option>
          <option value="api">API</option>
        </select>
      </div>
    </div>

    <div>
      <div class="section-title">Settings</div>
      <div class="toggle-row field">
        <label style="margin:0">Show Steps in Table</label>
        <label class="toggle"><input type="checkbox" id="settingSteps" checked onchange="renderTable()"><span class="slider"></span></label>
      </div>
      <div class="toggle-row field">
        <label style="margin:0">Auto-number IDs</label>
        <label class="toggle"><input type="checkbox" id="settingAutoId" checked><span class="slider"></span></label>
      </div>
      <div style="margin-top: 8px; display: flex; flex-direction: column; gap: 8px;">
        <button class="btn btn-secondary btn-sm btn-full" onclick="clearAll()">🗑 Clear All Test Cases</button>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <!-- STATS -->
    <div class="stats-bar" id="statsBar">
      <div class="stat-card">
        <div class="stat-label">Total Cases</div>
        <div class="stat-value accent" id="statTotal">0</div>
        <div class="progress-bar-wrap"><div class="progress-bar" id="pbTotal" style="width:100%"></div></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Passed</div>
        <div class="stat-value green" id="statPass">0</div>
        <div class="progress-bar-wrap"><div class="progress-bar" id="pbPass" style="width:0%;background:var(--green)"></div></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Failed</div>
        <div class="stat-value red" id="statFail">0</div>
        <div class="progress-bar-wrap"><div class="progress-bar" id="pbFail" style="width:0%;background:var(--red)"></div></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value yellow" id="statPend">0</div>
        <div class="progress-bar-wrap"><div class="progress-bar" id="pbPend" style="width:0%;background:var(--yellow)"></div></div>
      </div>
    </div>

    <!-- TOOLBAR -->
    <div class="toolbar">
      <div class="toolbar-left">
        <div class="search-box">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input type="text" id="searchInput" placeholder="Search test cases..." oninput="renderTable()"/>
        </div>
        <div class="filter-chips" id="typeChips"></div>
      </div>
      <div class="toolbar-right">
        <button class="btn btn-secondary btn-sm" onclick="selectAll()">☑ Select All</button>
        <button class="btn btn-danger btn-sm" onclick="deleteSelected()" id="btnDeleteSel" style="display:none">🗑 Delete Selected</button>
        <div class="dropdown" id="exportDrop">
          <button class="btn btn-green btn-sm" onclick="toggleExport()">⬇ Export ▾</button>
          <div class="dropdown-menu">
            <div class="dropdown-item" onclick="exportCSV()">📄 CSV</div>
            <div class="dropdown-item" onclick="exportJSON()">📦 JSON</div>
            <div class="dropdown-item" onclick="exportMarkdown()">📝 Markdown</div>
            <div class="dropdown-item" onclick="exportHTML()">🌐 HTML Report</div>
          </div>
        </div>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrap">
      <table id="testTable">
        <thead>
          <tr>
            <th style="width:36px"><input type="checkbox" id="checkAll" onchange="toggleAll(this)"/></th>
            <th onclick="sortBy('id')">ID ⇅</th>
            <th onclick="sortBy('title')">Title / Module ⇅</th>
            <th onclick="sortBy('type')">Type ⇅</th>
            <th onclick="sortBy('priority')">Priority ⇅</th>
            <th onclick="sortBy('status')">Status ⇅</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
      <div class="empty-state" id="emptyState">
        <div class="empty-icon">🧪</div>
        <p>No test cases yet. Use <strong>Quick Generate</strong> or click <strong>+ New Test Case</strong>.</p>
      </div>
    </div>
  </main>
</div>

<!-- ── MODAL ── -->
<div class="overlay" id="modalOverlay">
  <div class="modal">
    <div class="modal-header">
      <h2 id="modalTitle">New Test Case</h2>
      <button class="close-btn" onclick="closeModal()">✕</button>
    </div>

    <input type="hidden" id="tcEditId"/>

    <div class="field">
      <label>Test Case ID</label>
      <input type="text" id="tcId" placeholder="e.g. TC-001"/>
    </div>
    <div class="field">
      <label>Title</label>
      <input type="text" id="tcTitle" placeholder="Describe what is being tested"/>
    </div>
    <div class="field">
      <label>Module / Feature</label>
      <input type="text" id="tcModule" placeholder="e.g. Authentication"/>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <div class="field">
        <label>Type</label>
        <select id="tcType">
          <option value="functional">Functional</option>
          <option value="integration">Integration</option>
          <option value="regression">Regression</option>
          <option value="smoke">Smoke</option>
          <option value="performance">Performance</option>
          <option value="security">Security</option>
          <option value="ui">UI / UX</option>
          <option value="api">API</option>
        </select>
      </div>
      <div class="field">
        <label>Priority</label>
        <select id="tcPriority">
          <option value="critical">Critical</option>
          <option value="high">High</option>
          <option value="medium" selected>Medium</option>
          <option value="low">Low</option>
        </select>
      </div>
    </div>
    <div class="field">
      <label>Preconditions</label>
      <textarea id="tcPre" placeholder="What must be true before this test runs..."></textarea>
    </div>
    <div class="field">
      <label>Test Steps</label>
      <div class="steps-list" id="stepsList"></div>
      <button class="btn btn-secondary btn-sm" onclick="addStep()">＋ Add Step</button>
    </div>
    <div class="field">
      <label>Expected Result</label>
      <textarea id="tcExpected" placeholder="What should happen if test passes..."></textarea>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <div class="field">
        <label>Status</label>
        <select id="tcStatus">
          <option value="pending">Pending</option>
          <option value="pass">Pass</option>
          <option value="fail">Fail</option>
          <option value="skip">Skip</option>
        </select>
      </div>
      <div class="field">
        <label>Assigned To</label>
        <input type="text" id="tcAssigned" placeholder="Tester name"/>
      </div>
    </div>
    <div class="field">
      <label>Tags (press Enter to add)</label>
      <div class="tag-container" id="tagContainer" onclick="document.getElementById('tagInputField').focus()">
        <input class="tag-input" id="tagInputField" placeholder="add tag..." onkeydown="handleTagInput(event)"/>
      </div>
    </div>
    <div class="field">
      <label>Notes / Actual Result</label>
      <textarea id="tcNotes" placeholder="Actual result, bug ref, observations..."></textarea>
    </div>

    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
      <button class="btn btn-primary" onclick="saveTestCase()">💾 Save Test Case</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast"><span class="ti">✓</span><span id="toastMsg"></span></div>

<script>
// ── STATE ──
let testCases = JSON.parse(localStorage.getItem('testforge_cases') || '[]');
let currentTags = [];
let currentSteps = [];
let sortField = 'id';
let sortDir = 1;
let selectedIds = new Set();

// ── TEMPLATES ──
const templates = {
  functional: {
    login: [
      { title:'Valid Login', steps:['Navigate to login page','Enter valid username','Enter valid password','Click Login button'], expected:'User is redirected to dashboard' },
      { title:'Invalid Password', steps:['Navigate to login page','Enter valid username','Enter invalid password','Click Login button'], expected:'Error message "Invalid credentials" is displayed' },
      { title:'Empty Fields', steps:['Navigate to login page','Leave all fields empty','Click Login button'], expected:'Validation errors shown for required fields' },
      { title:'SQL Injection Attempt', steps:['Navigate to login page','Enter \' OR 1=1-- in username','Enter any password','Click Login button'], expected:'Login fails, no data exposed' },
      { title:'Remember Me', steps:['Navigate to login page','Enter valid credentials','Check "Remember Me"','Click Login'], expected:'Session persists after browser restart' },
    ],
    register: [
      { title:'Successful Registration', steps:['Go to register page','Fill all required fields with valid data','Submit form'], expected:'Account created, confirmation email sent' },
      { title:'Duplicate Email', steps:['Go to register page','Use an existing email','Submit form'], expected:'Error: Email already registered' },
      { title:'Password Mismatch', steps:['Go to register page','Enter different values in password and confirm','Submit'], expected:'Error: Passwords do not match' },
    ],
    cart: [
      { title:'Add Item to Cart', steps:['Browse product catalog','Select a product','Click Add to Cart'], expected:'Item appears in cart with correct quantity' },
      { title:'Remove Item from Cart', steps:['Open cart','Find an item','Click Remove'], expected:'Item removed, total updated' },
      { title:'Update Quantity', steps:['Open cart','Change item quantity','Confirm'], expected:'Quantity and total price updated correctly' },
    ],
  },
  smoke: [
    { title:'App Launches', steps:['Start application'], expected:'App loads without errors' },
    { title:'Homepage Loads', steps:['Navigate to homepage'], expected:'Page renders with all key elements visible' },
    { title:'Login Works', steps:['Login with valid credentials'], expected:'User successfully logged in' },
    { title:'Logout Works', steps:['Click logout'], expected:'User is logged out and redirected to login' },
    { title:'Navigation Menu', steps:['Click each nav item'], expected:'All pages load without 404 or errors' },
  ],
  regression: [
    { title:'Verify Previous Bug Fix #1', steps:['Reproduce original bug scenario'], expected:'Bug does not reoccur' },
    { title:'Core Flow Unchanged', steps:['Run main user flow end to end'], expected:'All steps complete successfully' },
    { title:'Data Integrity Check', steps:['Perform CRUD operations','Check database'], expected:'All records are saved and retrieved correctly' },
  ],
  api: [
    { title:'GET /users returns 200', steps:['Send GET /api/users with valid token'], expected:'Response status 200, body contains users array' },
    { title:'POST /users creates user', steps:['Send POST /api/users with valid payload'], expected:'Status 201, new user in response body' },
    { title:'Unauthorized Access', steps:['Send request without token'], expected:'Status 401 returned' },
    { title:'Invalid Payload', steps:['Send POST with missing required fields'], expected:'Status 400 with validation error details' },
    { title:'Rate Limiting', steps:['Send 100+ requests rapidly'], expected:'Status 429 returned after threshold' },
  ],
  security: [
    { title:'XSS Injection', steps:['Input a script tag in all text fields'], expected:'Script not executed, input sanitized' },
    { title:'CSRF Token Validation', steps:['Submit form without CSRF token'], expected:'Request rejected with 403' },
    { title:'Brute Force Protection', steps:['Attempt login 10 times with wrong password'], expected:'Account locked after threshold' },
    { title:'HTTPS Enforcement', steps:['Access app over HTTP'], expected:'Redirected to HTTPS' },
  ],
  performance: [
    { title:'Page Load < 3s', steps:['Load homepage on standard connection'], expected:'Page fully loaded in under 3 seconds' },
    { title:'100 Concurrent Users', steps:['Simulate 100 concurrent requests'], expected:'No errors, response time < 2s' },
    { title:'Database Query Optimization', steps:['Run heavy query','Check execution time'], expected:'Query completes in < 500ms' },
  ],
  integration: [
    { title:'Payment Gateway Integration', steps:['Complete checkout','Submit payment'], expected:'Payment processed, order confirmed, receipt emailed' },
    { title:'Email Service Integration', steps:['Trigger email event'], expected:'Email delivered within 60 seconds' },
    { title:'Third-party API Data Sync', steps:['Trigger sync','Wait for completion'], expected:'Data matches third-party source' },
  ],
  ui: [
    { title:'Mobile Responsiveness', steps:['Open app on mobile viewport (375px)'], expected:'Layout adapts, no horizontal scroll' },
    { title:'Keyboard Navigation', steps:['Tab through all interactive elements'], expected:'All elements reachable and focusable' },
    { title:'Color Contrast', steps:['Inspect text vs background colors'], expected:'WCAG AA contrast ratio met (4.5:1)' },
    { title:'Form Error Messages', steps:['Submit invalid data','Check error display'], expected:'Errors are visible and descriptive' },
  ],
};

// ── UTILS ──
function save() { localStorage.setItem('testforge_cases', JSON.stringify(testCases)); }

function nextId() {
  if (!document.getElementById('settingAutoId').checked) return 'TC-' + String(Date.now()).slice(-4);
  const nums = testCases.map(t => parseInt(t.id.replace(/\D/g,''))).filter(n => !isNaN(n));
  const next = nums.length ? Math.max(...nums) + 1 : 1;
  return 'TC-' + String(next).padStart(3, '0');
}

function toast(msg, type='success') {
  const t = document.getElementById('toast');
  const ti = t.querySelector('.ti');
  document.getElementById('toastMsg').textContent = msg;
  t.className = 'show ' + type;
  ti.textContent = type === 'success' ? '✓' : '✕';
  clearTimeout(t._t);
  t._t = setTimeout(() => t.classList.remove('show'), 3000);
}

function updateProjectName() {
  document.title = 'TestForge — ' + (document.getElementById('projectName').value || 'My Project');
}

// ── STATS ──
function updateStats() {
  const total = testCases.length;
  const pass = testCases.filter(t => t.status === 'pass').length;
  const fail = testCases.filter(t => t.status === 'fail').length;
  const pend = testCases.filter(t => t.status === 'pending' || t.status === 'skip').length;
  document.getElementById('statTotal').textContent = total;
  document.getElementById('statPass').textContent = pass;
  document.getElementById('statFail').textContent = fail;
  document.getElementById('statPend').textContent = pend;
  const pct = n => total ? Math.round(n/total*100) : 0;
  document.getElementById('pbPass').style.width = pct(pass) + '%';
  document.getElementById('pbFail').style.width = pct(fail) + '%';
  document.getElementById('pbPend').style.width = pct(pend) + '%';
}

// ── SORT ──
function sortBy(field) {
  if (sortField === field) sortDir *= -1;
  else { sortField = field; sortDir = 1; }
  renderTable();
}

// ── RENDER TABLE ──
function renderTable() {
  const search = document.getElementById('searchInput').value.toLowerCase();
  const fStatus = document.getElementById('filterStatus').value;
  const fPrio = document.getElementById('filterPriority').value;
  const fType = document.getElementById('filterType').value;
  const showSteps = document.getElementById('settingSteps').checked;

  let data = testCases.filter(t => {
    const matchSearch = !search ||
      t.id.toLowerCase().includes(search) ||
      t.title.toLowerCase().includes(search) ||
      (t.module||'').toLowerCase().includes(search) ||
      (t.tags||[]).some(g => g.toLowerCase().includes(search));
    const matchStatus = fStatus === 'all' || t.status === fStatus;
    const matchPrio = fPrio === 'all' || t.priority === fPrio;
    const matchType = fType === 'all' || t.type === fType;
    return matchSearch && matchStatus && matchPrio && matchType;
  });

  data.sort((a, b) => {
    let av = (a[sortField]||'').toLowerCase();
    let bv = (b[sortField]||'').toLowerCase();
    return av < bv ? -sortDir : av > bv ? sortDir : 0;
  });

  const tbody = document.getElementById('tableBody');
  const empty = document.getElementById('emptyState');

  if (data.length === 0) {
    tbody.innerHTML = '';
    empty.style.display = 'block';
    updateStats();
    return;
  }
  empty.style.display = 'none';

  const prioClass = { critical:'p-critical', high:'p-high', medium:'p-medium', low:'p-low' };
  const dotClass = { pass:'dot-pass', fail:'dot-fail', skip:'dot-skip', pending:'dot-pending' };
  const statusLabel = { pass:'Pass', fail:'Fail', skip:'Skip', pending:'Pending' };

  tbody.innerHTML = data.map(t => {
    const steps = showSteps && t.steps && t.steps.length
      ? `<div style="margin-top:4px;color:var(--muted);font-size:0.75rem">${t.steps.length} step${t.steps.length>1?'s':''}</div>` : '';
    const tags = t.tags && t.tags.length
      ? `<div style="margin-top:4px;display:flex;gap:4px;flex-wrap:wrap">${t.tags.map(g=>`<span style="background:rgba(108,99,255,.15);color:var(--accent);border-radius:4px;padding:1px 6px;font-size:.7rem">${g}</span>`).join('')}</div>` : '';
    const sel = selectedIds.has(t.id) ? 'selected' : '';
    return `
    <tr class="${sel}" id="row-${t.id}">
      <td><input type="checkbox" ${sel?'checked':''} onchange="toggleSelect('${t.id}',this)"/></td>
      <td><span class="tc-id">${t.id}</span></td>
      <td><div class="tc-title">${t.title}${t.module?`<small>${t.module}</small>`:''}${steps}${tags}</div></td>
      <td><span style="font-size:.78rem;color:var(--muted)">${t.type||'-'}</span></td>
      <td><span class="priority-badge ${prioClass[t.priority]||'p-medium'}">${t.priority||'medium'}</span></td>
      <td>
        <select class="status-select" onchange="changeStatus('${t.id}',this.value)" style="background:transparent;border:none;color:var(--text);font-size:.82rem;cursor:pointer;outline:none">
          ${['pending','pass','fail','skip'].map(s=>`<option value="${s}" ${t.status===s?'selected':''}>${statusLabel[s]}</option>`).join('')}
        </select>
        <span class="dot ${dotClass[t.status]||'dot-pending'}" style="display:inline-block"></span>
      </td>
      <td>
        <div class="action-btns">
          <button class="icon-btn" onclick="openModal('edit','${t.id}')" title="Edit">✏️</button>
          <button class="icon-btn" onclick="viewTC('${t.id}')" title="View">👁</button>
          <button class="icon-btn del" onclick="deleteTC('${t.id}')" title="Delete">🗑</button>
        </div>
      </td>
    </tr>`;
  }).join('');

  updateStats();
  document.getElementById('btnDeleteSel').style.display = selectedIds.size ? 'inline-flex' : 'none';
}

function changeStatus(id, val) {
  const tc = testCases.find(t => t.id === id);
  if (tc) { tc.status = val; save(); renderTable(); }
}

// ── SELECTION ──
function toggleSelect(id, cb) {
  if (cb.checked) selectedIds.add(id); else selectedIds.delete(id);
  const row = document.getElementById('row-' + id);
  if (row) row.classList.toggle('selected', cb.checked);
  document.getElementById('btnDeleteSel').style.display = selectedIds.size ? 'inline-flex' : 'none';
}
function toggleAll(cb) {
  const rows = document.querySelectorAll('#tableBody tr');
  rows.forEach(r => {
    const chk = r.querySelector('input[type=checkbox]');
    const id = r.id.replace('row-','');
    if (chk) { chk.checked = cb.checked; if(cb.checked) selectedIds.add(id); else selectedIds.delete(id); }
    r.classList.toggle('selected', cb.checked);
  });
  document.getElementById('btnDeleteSel').style.display = selectedIds.size ? 'inline-flex' : 'none';
}
function selectAll() {
  document.getElementById('checkAll').checked = true;
  toggleAll(document.getElementById('checkAll'));
}
function deleteSelected() {
  if (!selectedIds.size) return;
  if (!confirm(`Delete ${selectedIds.size} selected test case(s)?`)) return;
  testCases = testCases.filter(t => !selectedIds.has(t.id));
  selectedIds.clear(); save(); renderTable(); toast('Selected test cases deleted');
}

// ── MODAL ──
function openModal(mode, id) {
  clearModalForm();
  if (mode === 'edit' && id) {
    const tc = testCases.find(t => t.id === id);
    if (!tc) return;
    document.getElementById('modalTitle').textContent = 'Edit Test Case';
    document.getElementById('tcEditId').value = id;
    document.getElementById('tcId').value = tc.id;
    document.getElementById('tcTitle').value = tc.title;
    document.getElementById('tcModule').value = tc.module || '';
    document.getElementById('tcType').value = tc.type || 'functional';
    document.getElementById('tcPriority').value = tc.priority || 'medium';
    document.getElementById('tcPre').value = tc.preconditions || '';
    document.getElementById('tcExpected').value = tc.expected || '';
    document.getElementById('tcStatus').value = tc.status || 'pending';
    document.getElementById('tcAssigned').value = tc.assigned || '';
    document.getElementById('tcNotes').value = tc.notes || '';
    currentTags = [...(tc.tags||[])];
    currentSteps = [...(tc.steps||[])];
  } else {
    document.getElementById('modalTitle').textContent = 'New Test Case';
    document.getElementById('tcId').value = nextId();
    document.getElementById('tcAssigned').value = document.getElementById('testerName').value;
    currentTags = []; currentSteps = [];
  }
  renderTagsUI(); renderSteps();
  document.getElementById('modalOverlay').classList.add('active');
}
function closeModal() { document.getElementById('modalOverlay').classList.remove('active'); }
function clearModalForm() {
  ['tcEditId','tcId','tcTitle','tcModule','tcPre','tcExpected','tcAssigned','tcNotes'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('tcType').value = 'functional';
  document.getElementById('tcPriority').value = 'medium';
  document.getElementById('tcStatus').value = 'pending';
  currentTags = []; currentSteps = [];
  renderTagsUI(); renderSteps();
}
function saveTestCase() {
  const id = document.getElementById('tcId').value.trim();
  const title = document.getElementById('tcTitle').value.trim();
  if (!id || !title) { toast('ID and Title are required', 'error'); return; }
  const editId = document.getElementById('tcEditId').value;
  const tc = {
    id, title,
    module: document.getElementById('tcModule').value.trim(),
    type: document.getElementById('tcType').value,
    priority: document.getElementById('tcPriority').value,
    preconditions: document.getElementById('tcPre').value.trim(),
    steps: [...currentSteps],
    expected: document.getElementById('tcExpected').value.trim(),
    status: document.getElementById('tcStatus').value,
    assigned: document.getElementById('tcAssigned').value.trim(),
    tags: [...currentTags],
    notes: document.getElementById('tcNotes').value.trim(),
    createdAt: editId ? (testCases.find(t=>t.id===editId)||{}).createdAt || Date.now() : Date.now(),
  };
  if (editId) {
    const idx = testCases.findIndex(t => t.id === editId);
    if (idx !== -1) testCases[idx] = tc;
    else testCases.push(tc);
  } else {
    if (testCases.find(t => t.id === id)) { toast('ID already exists', 'error'); return; }
    testCases.push(tc);
  }
  save(); renderTable(); closeModal();
  toast(editId ? 'Test case updated!' : 'Test case created!');
}

// ── STEPS ──
function renderSteps() {
  const list = document.getElementById('stepsList');
  if (!currentSteps.length) { list.innerHTML = ''; return; }
  list.innerHTML = '';
  currentSteps.forEach((s, i) => {
    const row = document.createElement('div');
    row.className = 'step-row';
    const num = document.createElement('div');
    num.className = 'step-num';
    num.textContent = i + 1;
    const inp = document.createElement('input');
    inp.className = 'step-input';
    inp.value = s;
    inp.placeholder = 'Step ' + (i + 1);
    inp.oninput = function() { currentSteps[i] = this.value; };
    const del = document.createElement('button');
    del.className = 'step-del';
    del.textContent = '✕';
    del.type = 'button';
    del.onclick = function() { removeStep(i); };
    row.appendChild(num); row.appendChild(inp); row.appendChild(del);
    list.appendChild(row);
  });
}
function addStep() {
  currentSteps.push('');
  renderSteps();
  setTimeout(() => {
    const inputs = document.querySelectorAll('.step-input');
    if (inputs.length) inputs[inputs.length - 1].focus();
  }, 50);
}
function removeStep(i) { currentSteps.splice(i, 1); renderSteps(); }

// ── TAGS ──
function renderTagsUI() {
  const container = document.getElementById('tagContainer');
  // Remove old tag spans but keep the input
  Array.from(container.querySelectorAll('.tag')).forEach(el => el.remove());
  const input = document.getElementById('tagInputField');
  // Re-insert tag spans before the input
  currentTags.forEach((t, i) => {
    const span = document.createElement('span');
    span.className = 'tag';
    const txt = document.createTextNode(t + ' ');
    const rm = document.createElement('span');
    rm.className = 'remove';
    rm.textContent = '✕';
    rm.onclick = function() { removeTag(i); };
    span.appendChild(txt);
    span.appendChild(rm);
    container.insertBefore(span, input);
  });
}
function handleTagInput(e) {
  if ((e.key === 'Enter' || e.key === ',') && e.target.value.trim()) {
    e.preventDefault();
    const val = e.target.value.trim().replace(/,/g, '');
    if (val && !currentTags.includes(val)) { currentTags.push(val); renderTagsUI(); }
    e.target.value = '';
  } else if (e.key === 'Backspace' && !e.target.value && currentTags.length) {
    currentTags.pop(); renderTagsUI();
  }
}
function removeTag(i) { currentTags.splice(i, 1); renderTagsUI(); }

// ── VIEW ──
function viewTC(id) {
  const tc = testCases.find(t => t.id === id);
  if (!tc) return;
  alert(
    `[${tc.id}] ${tc.title}\n` +
    `Module: ${tc.module||'-'}\n` +
    `Type: ${tc.type} | Priority: ${tc.priority} | Status: ${tc.status}\n` +
    `Assigned: ${tc.assigned||'-'}\n\n` +
    `Preconditions:\n${tc.preconditions||'-'}\n\n` +
    `Steps:\n${(tc.steps||[]).map((s,i)=>`  ${i+1}. ${s}`).join('\n')||'-'}\n\n` +
    `Expected:\n${tc.expected||'-'}\n\n` +
    `Notes:\n${tc.notes||'-'}`
  );
}

// ── DELETE ──
function deleteTC(id) {
  if (!confirm('Delete this test case?')) return;
  testCases = testCases.filter(t => t.id !== id);
  selectedIds.delete(id); save(); renderTable(); toast('Test case deleted');
}
function clearAll() {
  if (!confirm('Clear ALL test cases? This cannot be undone.')) return;
  testCases = []; selectedIds.clear(); save(); renderTable(); toast('All test cases cleared');
}

// ── QUICK GENERATE ──
function quickGenerate() {
  const feature = document.getElementById('qgFeature').value.trim().toLowerCase();
  const type = document.getElementById('qgType').value;
  const priority = document.getElementById('qgPriority').value;
  const count = Math.max(1, parseInt(document.getElementById('qgCount').value) || 5);
  const module = document.getElementById('qgFeature').value.trim() || 'General';

  let pool = [];
  if (templates[type] && Array.isArray(templates[type])) {
    pool = templates[type];
  } else if (templates.functional) {
    const keys = Object.keys(templates.functional);
    const match = keys.find(k => feature.includes(k)) || keys[0];
    pool = templates.functional[match];
  }

  if (!pool || !pool.length) pool = [
    { title:'Positive Flow Test', steps:['Navigate to feature','Perform valid action'], expected:'Feature behaves as expected' },
    { title:'Negative Flow Test', steps:['Navigate to feature','Perform invalid action'], expected:'Appropriate error is shown' },
    { title:'Edge Case Test', steps:['Navigate to feature','Perform edge case action'], expected:'Handled gracefully' },
  ];

  let added = 0;
  for (let i = 0; added < count; i++) {
    const tpl = pool[i % pool.length];
    // ensure unique ID by pushing first so nextId increments correctly
    const id = nextId();
    testCases.push({
      id, title: tpl.title + (i >= pool.length ? ' #' + (Math.floor(i / pool.length) + 1) : ''),
      module, type, priority,
      preconditions: '',
      steps: [...tpl.steps],
      expected: tpl.expected,
      status: 'pending',
      assigned: document.getElementById('testerName').value,
      tags: [type, priority],
      notes: '',
      createdAt: Date.now() + i,
    });
    added++;
    // safety: stop after count * 3 iterations to prevent infinite loop
    if (i > count * 3) break;
  }

  save(); renderTable(); toast('Generated ' + added + ' test case(s)!');
}

// ── EXPORT ──
function toggleExport() { document.getElementById('exportDrop').classList.toggle('open'); }
document.addEventListener('click', e => { if (!e.target.closest('#exportDrop')) document.getElementById('exportDrop').classList.remove('open'); });

function exportCSV() {
  const headers = ['ID','Title','Module','Type','Priority','Status','Preconditions','Steps','Expected','Assigned','Tags','Notes'];
  const rows = testCases.map(t => [
    t.id, t.title, t.module||'', t.type, t.priority, t.status,
    (t.preconditions||'').replace(/\n/g,' '),
    (t.steps||[]).join(' | '),
    (t.expected||'').replace(/\n/g,' '),
    t.assigned||'',
    (t.tags||[]).join(', '),
    (t.notes||'').replace(/\n/g,' ')
  ].map(c => `"${String(c).replace(/"/g,'""')}"`));
  const csv = [headers.join(','), ...rows.map(r=>r.join(','))].join('\n');
  download('testcases.csv', csv, 'text/csv');
  toast('Exported as CSV');
}

function exportJSON() {
  download('testcases.json', JSON.stringify(testCases, null, 2), 'application/json');
  toast('Exported as JSON');
}

function exportMarkdown() {
  const proj = document.getElementById('projectName').value || 'Project';
  const ver = document.getElementById('projectVersion').value || '';
  let md = `# Test Cases — ${proj} ${ver}\n\n`;
  md += `| ID | Title | Module | Type | Priority | Status |\n|---|---|---|---|---|---|\n`;
  testCases.forEach(t => {
    md += `| ${t.id} | ${t.title} | ${t.module||'-'} | ${t.type} | ${t.priority} | ${t.status} |\n`;
  });
  md += `\n---\n\n`;
  testCases.forEach(t => {
    md += `## ${t.id}: ${t.title}\n`;
    md += `- **Module:** ${t.module||'-'}\n- **Type:** ${t.type}\n- **Priority:** ${t.priority}\n- **Status:** ${t.status}\n- **Assigned:** ${t.assigned||'-'}\n\n`;
    if (t.preconditions) md += `**Preconditions:**\n${t.preconditions}\n\n`;
    if (t.steps && t.steps.length) { md += `**Steps:**\n`; t.steps.forEach((s,i) => md += `${i+1}. ${s}\n`); md += '\n'; }
    if (t.expected) md += `**Expected Result:**\n${t.expected}\n\n`;
    if (t.notes) md += `**Notes:**\n${t.notes}\n\n`;
    md += '---\n\n';
  });
  download('testcases.md', md, 'text/markdown');
  toast('Exported as Markdown');
}

function exportHTML() {
  const proj = document.getElementById('projectName').value || 'Project';
  const ver = document.getElementById('projectVersion').value || '';
  const total = testCases.length;
  const pass = testCases.filter(t=>t.status==='pass').length;
  const fail = testCases.filter(t=>t.status==='fail').length;
  const passPct = total ? Math.round(pass/total*100) : 0;

  const rows = testCases.map(t => `
  <tr>
    <td>${t.id}</td><td>${t.title}</td><td>${t.module||'-'}</td>
    <td>${t.type}</td>
    <td class="p-${t.priority}">${t.priority}</td>
    <td class="s-${t.status}">${t.status}</td>
    <td>${(t.steps||[]).map((s,i)=>`${i+1}. ${s}`).join('<br>')}</td>
    <td>${t.expected||'-'}</td>
  </tr>`).join('');

  const html = `<!DOCTYPE html><html><head><meta charset="UTF-8"/><title>${proj} Test Report</title>
  <style>body{font-family:sans-serif;background:#f7f8fa;color:#222;margin:0;padding:24px}
  h1{color:#3b82d4}table{width:100%;border-collapse:collapse;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.1)}
  th{background:#3b82d4;color:#fff;padding:10px 12px;text-align:left;font-size:.8rem}
  td{padding:9px 12px;border-bottom:1px solid #e5e7eb;font-size:.83rem;vertical-align:top}
  tr:last-child td{border:none}.stat{display:inline-block;background:#fff;border-radius:8px;padding:12px 20px;margin:6px;box-shadow:0 1px 4px rgba(0,0,0,.08)}
  .p-critical{color:#ff5370;font-weight:700}.p-high{color:#ffab40;font-weight:700}.p-medium{color:#ffd740}.p-low{color:#00e676}
  .s-pass{color:#00e676;font-weight:700}.s-fail{color:#ff5370;font-weight:700}.s-skip{color:#ffd740}.s-pending{color:#8892b0}
  </style></head><body>
  <h1>📋 ${proj} — Test Report ${ver}</h1>
  <p>Generated: ${new Date().toLocaleString()}</p>
  <div><span class="stat">Total: <strong>${total}</strong></span><span class="stat" style="color:#00e676">Pass: <strong>${pass}</strong></span><span class="stat" style="color:#ff5370">Fail: <strong>${fail}</strong></span><span class="stat">Pass Rate: <strong>${passPct}%</strong></span></div>
  <br><table><thead><tr><th>ID</th><th>Title</th><th>Module</th><th>Type</th><th>Priority</th><th>Status</th><th>Steps</th><th>Expected</th></tr></thead>
  <tbody>${rows}</tbody></table></body></html>`;

  download(`${proj.replace(/\s+/g,'-')}-test-report.html`, html, 'text/html');
  toast('Exported as HTML Report');
}

function download(name, content, type) {
  const a = document.createElement('a');
  a.href = URL.createObjectURL(new Blob([content], {type}));
  a.download = name; a.click();
  URL.revokeObjectURL(a.href);
}

// ── INIT ──
renderTable();
</script>
</body>
</html>
