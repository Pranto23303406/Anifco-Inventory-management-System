<?php
include("db.php");
$result = $conn->query("SELECT * FROM invoices ORDER BY id DESC");
$rows = [];
while($r = $result->fetch_assoc()) $rows[] = $r;

// Stats
$total_sell  = 0; $count_sell  = 0;
$total_order = 0; $count_order = 0;

foreach($rows as $r) {
    $amt = (float)($r['grand_total'] ?? 0);
    if(strtolower($r['type'] ?? '') === 'sell') { $total_sell  += $amt; $count_sell++; }
    else                                        { $total_order += $amt; $count_order++; }
}
$grand = $total_sell + $total_order;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Accounts Summary — ANIFCO</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
:root {
  --navy:    #0a2540;
  --blue:    #1a73e8;
  --blue2:   #0d5db5;
  --sky:     #e8f1fd;
  --green:   #0e9f6e;
  --green-bg:#ecfdf5;
  --green-t: #065f46;
  --blue-t:  #1e40af;
  --red:     #dc2626;
  --red-bg:  #fef2f2;
  --amber:   #d97706;
  --amber-bg:#fffbeb;
  --surface: #f4f8ff;
  --card:    #ffffff;
  --border:  #e2eaf5;
  --text1:   #0a2540;
  --text2:   #4a5a72;
  --text3:   #8a98ac;
  --radius:  14px;
  --shadow:  0 2px 16px rgba(10,37,64,.07);
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--surface);
  min-height: 100vh;
  color: var(--text1);
}

/* ── Topbar ── */
.topbar {
  background: var(--navy);
  padding: 0 40px;
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 16px rgba(0,0,0,.2);
}

.brand { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -.3px; }
.brand-sep { width:1px; height:22px; background:rgba(255,255,255,.2); margin: 0 14px; }
.brand-sub { font-size:14px; color:rgba(255,255,255,.5); font-weight:400; }

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 8px 18px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  background: rgba(255,255,255,.08);
  color: rgba(255,255,255,.85);
  border: 1px solid rgba(255,255,255,.18);
  transition: .15s;
}
.back-btn:hover { background: rgba(255,255,255,.16); }

/* ── Wrapper ── */
.wrapper {
  max-width: 1200px;
  margin: 0 auto;
  padding: 36px 32px 80px;
}

/* ── Page head ── */
.page-head {
  margin-bottom: 28px;
}
.page-head h1 {
  font-size: 26px;
  font-weight: 700;
  letter-spacing: -.4px;
  margin-bottom: 5px;
}
.page-head p { font-size: 14px; color: var(--text3); }

/* ── Summary cards ── */
.summary-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  margin-bottom: 28px;
}

.sum-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px 22px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: var(--shadow);
}

.sum-icon {
  width: 46px;
  height: 46px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  flex-shrink: 0;
}

.sum-icon.blue   { background: var(--sky);      color: var(--blue); }
.sum-icon.green  { background: var(--green-bg);  color: var(--green); }
.sum-icon.indigo { background: #eef2ff;           color: #4338ca; }
.sum-icon.amber  { background: var(--amber-bg);  color: var(--amber); }

.sum-info label {
  font-size: 12px;
  color: var(--text3);
  font-weight: 500;
  display: block;
  margin-bottom: 3px;
  text-transform: uppercase;
  letter-spacing: .4px;
}

.sum-info strong {
  font-size: 20px;
  font-weight: 700;
  color: var(--text1);
  font-family: 'DM Mono', monospace;
}

/* ── Main card ── */
.main-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  overflow: hidden;
}

/* ── Card header ── */
.card-head {
  background: linear-gradient(135deg, var(--navy) 0%, #1a406e 100%);
  padding: 20px 26px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
}

.card-head h2 {
  font-size: 16px;
  font-weight: 600;
  color: #fff;
  display: flex;
  align-items: center;
  gap: 8px;
}

.record-badge {
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.25);
  color: #fff;
  border-radius: 20px;
  padding: 4px 14px;
  font-size: 12px;
  font-weight: 600;
}

/* ── Toolbar ── */
.toolbar {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 20px;
  border-bottom: 1px solid var(--border);
  flex-wrap: wrap;
}

.search-box {
  flex: 1;
  min-width: 180px;
  display: flex;
  align-items: center;
  gap: 9px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 0 14px;
  height: 38px;
  transition: .15s;
}
.search-box:focus-within { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(26,115,232,.1); }
.search-box i { color: var(--text3); font-size: 16px; }
.search-box input {
  border: none; outline: none;
  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  color: var(--text1);
  width: 100%;
  background: transparent;
}
.search-box input::placeholder { color: var(--text3); }

.flt-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  height: 38px;
  padding: 0 16px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--card);
  font-family: 'DM Sans', sans-serif;
  font-size: 12px;
  font-weight: 600;
  color: var(--text2);
  cursor: pointer;
  transition: .15s;
  white-space: nowrap;
}
.flt-btn:hover, .flt-btn.active { border-color: var(--blue); color: var(--blue); background: var(--sky); }

/* ── Table ── */
.table-wrap { overflow-x: auto; }

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 640px;
}

thead th {
  padding: 11px 16px;
  font-size: 11px;
  font-weight: 700;
  color: var(--text3);
  text-transform: uppercase;
  letter-spacing: .6px;
  background: #f7fafc;
  border-bottom: 2px solid var(--border);
  text-align: left;
  white-space: nowrap;
}

tbody td {
  padding: 13px 16px;
  font-size: 14px;
  color: var(--text1);
  border-bottom: 1px solid #f0f4f8;
  vertical-align: middle;
}

tbody tr:last-child td { border-bottom: none; }
tbody tr { transition: background .1s; }
tbody tr:hover { background: #f8faff; }

.id-cell {
  font-family: 'DM Mono', monospace;
  font-size: 12px;
  color: var(--text3);
}

/* type badge */
.type-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  border-radius: 20px;
  padding: 4px 12px;
  font-size: 12px;
  font-weight: 700;
}
.type-badge.sell  { background: var(--green-bg); color: var(--green-t); }
.type-badge.order { background: #eef2ff;          color: #3730a3; }

/* amount */
.amount-cell {
  font-family: 'DM Mono', monospace;
  font-size: 14px;
  font-weight: 600;
  color: var(--text1);
}

/* payment */
.pay-chip {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 3px 10px;
  font-size: 12px;
  color: var(--text2);
  font-weight: 500;
}

/* empty */
.empty-row td {
  text-align: center;
  padding: 60px;
  color: var(--text3);
}
.empty-row i { font-size: 40px; display: block; margin-bottom: 10px; }

/* footer */
.card-foot {
  padding: 12px 20px;
  background: #f7fafc;
  border-top: 1px solid var(--border);
  font-size: 13px;
  color: var(--text3);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.total-line {
  font-size: 14px;
  font-weight: 700;
  color: var(--text1);
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: 'DM Mono', monospace;
}

@media (max-width: 640px) {
  .topbar { padding: 0 16px; }
  .brand-sep, .brand-sub { display: none; }
  .wrapper { padding: 20px 14px 60px; }
  .summary-row { grid-template-columns: 1fr 1fr; }
}
</style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
  <div style="display:flex;align-items:center;">
    <div class="brand">ANIFCO</div>
    <div class="brand-sep"></div>
    <div class="brand-sub">Accounts</div>
  </div>
  <a href="dashboard.php" class="back-btn">
    <i class="ti ti-arrow-left"></i> Back
  </a>
</div>

<div class="wrapper">

  <!-- Page head -->
  <div class="page-head">
    <h1>Accounts Summary</h1>
    <p>All invoices and transactions overview</p>
  </div>

  <!-- Summary cards -->
  <div class="summary-row">
    <div class="sum-card">
      <div class="sum-icon blue"><i class="ti ti-file-invoice"></i></div>
      <div class="sum-info">
        <label>Total Records</label>
        <strong><?php echo count($rows); ?></strong>
      </div>
    </div>
    <div class="sum-card">
      <div class="sum-icon green"><i class="ti ti-trending-up"></i></div>
      <div class="sum-info">
        <label>Total Sales</label>
        <strong><?php echo number_format($total_sell, 2); ?></strong>
      </div>
    </div>
    <div class="sum-card">
      <div class="sum-icon indigo"><i class="ti ti-shopping-cart"></i></div>
      <div class="sum-info">
        <label>Total Orders</label>
        <strong><?php echo number_format($total_order, 2); ?></strong>
      </div>
    </div>
    <div class="sum-card">
      <div class="sum-icon amber"><i class="ti ti-coin"></i></div>
      <div class="sum-info">
        <label>Grand Total</label>
        <strong><?php echo number_format($grand, 2); ?></strong>
      </div>
    </div>
  </div>

  <!-- Main card -->
  <div class="main-card">

    <!-- Card head -->
    <div class="card-head">
      <h2><i class="ti ti-table"></i> Invoice Records</h2>
      <div class="record-badge"><?php echo count($rows); ?> records</div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
      <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" id="searchInput" placeholder="Search by name, item, payment…" oninput="filterTable()">
      </div>
      <button class="flt-btn active" id="flt-all"   onclick="setFilter('all')">All</button>
      <button class="flt-btn"        id="flt-sell"  onclick="setFilter('sell')">
        <i class="ti ti-trending-up" style="font-size:12px;"></i> Sell
      </button>
      <button class="flt-btn"        id="flt-order" onclick="setFilter('order')">
        <i class="ti ti-shopping-cart" style="font-size:12px;"></i> Order
      </button>
    </div>

    <!-- Table -->
    <div class="table-wrap">
      <table id="accTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Name</th>
            <th>Item</th>
            <th>Amount</th>
            <th>Payment</th>
          </tr>
        </thead>
        <tbody>
          <?php if(count($rows) === 0): ?>
            <tr class="empty-row">
              <td colspan="6">
                <i class="ti ti-file-off"></i>
                No records found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach($rows as $r):
              $type = strtolower(trim($r['type'] ?? ''));
              $pay  = strtolower(trim($r['payment_method'] ?? ''));
              $pay_icons = ['cash'=>'ti-cash','bank'=>'ti-building-bank','cheque'=>'ti-checkup-list','card'=>'ti-credit-card','mobile'=>'ti-device-mobile'];
              $pay_icon = 'ti-wallet';
              foreach($pay_icons as $k => $v) { if(str_contains($pay, $k)) { $pay_icon = $v; break; } }
            ?>
            <tr data-type="<?php echo htmlspecialchars($type); ?>">
              <td class="id-cell">#<?php echo htmlspecialchars($r['id']); ?></td>
              <td>
                <span class="type-badge <?php echo $type === 'sell' ? 'sell' : 'order'; ?>">
                  <i class="ti <?php echo $type === 'sell' ? 'ti-trending-up' : 'ti-shopping-cart'; ?>" style="font-size:10px;"></i>
                  <?php echo ucfirst(htmlspecialchars($r['type'] ?? '')); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($r['name'] ?? '—'); ?></td>
              <td style="color:var(--text2);"><?php echo htmlspecialchars($r['item_name'] ?? '—'); ?></td>
              <td class="amount-cell">৳ <?php echo number_format((float)($r['grand_total'] ?? 0), 2); ?></td>
              <td>
                <span class="pay-chip">
                  <i class="ti <?php echo $pay_icon; ?>" style="font-size:12px;"></i>
                  <?php echo htmlspecialchars($r['payment_method'] ?? '—'); ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Footer -->
    <div class="card-foot">
      <span id="visibleCount">Showing <?php echo count($rows); ?> records</span>
      <div class="total-line">
        <i class="ti ti-sigma" style="font-size:16px; color:var(--text3);"></i>
        Grand Total: ৳ <?php echo number_format($grand, 2); ?>
      </div>
    </div>

  </div><!-- /main-card -->

</div><!-- /wrapper -->

<script>
let currentFilter = 'all';

function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  let visible = 0;
  document.querySelectorAll('#accTable tbody tr:not(.empty-row)').forEach(row => {
    const text = row.innerText.toLowerCase();
    const type = row.dataset.type || '';
    const matchQ = text.includes(q);
    const matchF = currentFilter === 'all' || type === currentFilter;
    const show = matchQ && matchF;
    row.style.display = show ? '' : 'none';
    if(show) visible++;
  });
  document.getElementById('visibleCount').textContent = 'Showing ' + visible + ' records';
}

function setFilter(f) {
  currentFilter = f;
  ['all','sell','order'].forEach(x => {
    document.getElementById('flt-' + x).classList.toggle('active', x === f);
  });
  filterTable();
}
</script>

</body>
</html>