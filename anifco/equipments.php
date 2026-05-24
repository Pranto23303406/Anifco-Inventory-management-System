<?php
include("db.php");
$result = $conn->query("SELECT * FROM equipments ORDER BY id DESC");
$equipments = [];
while($row = $result->fetch_assoc()) $equipments[] = $row;
$total = count($equipments);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Equipments — ANIFCO</title>
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
  --amber:   #d97706;
  --amber-bg:#fffbeb;
  --red:     #dc2626;
  --red-bg:  #fef2f2;
  --surface: #f4f8ff;
  --card:    #ffffff;
  --border:  #e2eaf5;
  --text1:   #0a2540;
  --text2:   #4a5a72;
  --text3:   #8a98ac;
  --radius:  14px;
  --shadow:  0 2px 16px rgba(10,37,64,.07);
  --shadow-hover: 0 8px 30px rgba(10,37,64,.13);
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
  box-shadow: 0 2px 16px rgba(0,0,0,.18);
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.brand {
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  letter-spacing: -.3px;
}

.brand-sep {
  width: 1px;
  height: 22px;
  background: rgba(255,255,255,.2);
}

.brand-sub {
  font-size: 14px;
  color: rgba(255,255,255,.55);
  font-weight: 400;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 10px;
}

.nav-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 8px 18px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: .15s;
  white-space: nowrap;
}

.nav-btn.outline {
  background: rgba(255,255,255,.08);
  color: rgba(255,255,255,.85);
  border: 1px solid rgba(255,255,255,.18);
}
.nav-btn.outline:hover { background: rgba(255,255,255,.15); }

.nav-btn.solid {
  background: var(--blue);
  color: #fff;
  border: 1px solid transparent;
}
.nav-btn.solid:hover { background: var(--blue2); }

/* ── Page wrapper ── */
.wrapper {
  max-width: 1280px;
  margin: 0 auto;
  padding: 36px 32px 80px;
}

/* ── Page head ── */
.page-head {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 16px;
  margin-bottom: 28px;
}

.page-head-left h1 {
  font-size: 26px;
  font-weight: 700;
  color: var(--text1);
  letter-spacing: -.4px;
  line-height: 1;
  margin-bottom: 6px;
}

.page-head-left p {
  font-size: 14px;
  color: var(--text3);
}

.head-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

/* ── Toolbar (search + filter) ── */
.toolbar {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 28px;
  flex-wrap: wrap;
}

.search-box {
  flex: 1;
  min-width: 200px;
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 0 16px;
  height: 44px;
  transition: border-color .15s, box-shadow .15s;
}
.search-box:focus-within {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(26,115,232,.1);
}
.search-box i { color: var(--text3); font-size: 18px; flex-shrink: 0; }
.search-box input {
  border: none; outline: none;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  color: var(--text1);
  width: 100%;
  background: transparent;
}
.search-box input::placeholder { color: var(--text3); }

.filter-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--card);
  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  font-weight: 600;
  color: var(--text2);
  cursor: pointer;
  transition: .15s;
  white-space: nowrap;
}
.filter-btn:hover { border-color: var(--blue); color: var(--blue); }
.filter-btn.active { background: var(--sky); border-color: var(--blue); color: var(--blue); }

/* ── Stats row ── */
.stats-row {
  display: flex;
  gap: 12px;
  margin-bottom: 28px;
  flex-wrap: wrap;
}

.stat-card {
  flex: 1;
  min-width: 140px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 14px;
}

.stat-icon {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
}

.stat-icon.blue   { background: var(--sky);      color: var(--blue); }
.stat-icon.green  { background: var(--green-bg);  color: var(--green); }
.stat-icon.amber  { background: var(--amber-bg);  color: var(--amber); }

.stat-info label {
  font-size: 12px;
  color: var(--text3);
  font-weight: 500;
  display: block;
  margin-bottom: 2px;
}
.stat-info strong {
  font-size: 22px;
  font-weight: 700;
  color: var(--text1);
}

/* ── Grid ── */
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 22px;
}

/* ── Equipment Card ── */
.eq-card {
  background: var(--card);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: transform .2s, box-shadow .2s;
  display: flex;
  flex-direction: column;
}

.eq-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-hover);
}

.eq-img-wrap {
  position: relative;
  width: 100%;
  height: 190px;
  background: #f0f4fb;
  overflow: hidden;
}

.eq-img-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .35s;
}
.eq-card:hover .eq-img-wrap img { transform: scale(1.05); }

.eq-img-wrap .no-img {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #c5d0e0;
  font-size: 52px;
}

/* stock ribbon */
.stock-ribbon {
  position: absolute;
  top: 12px;
  right: 12px;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  border-radius: 20px;
  padding: 4px 11px;
  font-size: 12px;
  font-weight: 700;
  backdrop-filter: blur(6px);
}

.stock-ribbon.in    { background: rgba(236,253,245,.9); color: #065f46; }
.stock-ribbon.low   { background: rgba(255,251,235,.9); color: #92400e; }
.stock-ribbon.out   { background: rgba(254,242,242,.9); color: #991b1b; }

.eq-body {
  padding: 16px 18px 18px;
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.eq-name {
  font-size: 16px;
  font-weight: 700;
  color: var(--text1);
  line-height: 1.3;
}

.eq-company {
  font-size: 13px;
  color: var(--text2);
  display: flex;
  align-items: center;
  gap: 5px;
}

.eq-footer {
  margin-top: auto;
  padding-top: 12px;
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.stock-count {
  font-family: 'DM Mono', monospace;
  font-size: 13px;
  font-weight: 500;
  color: var(--text2);
}

.stock-count span {
  font-size: 18px;
  font-weight: 700;
  color: var(--text1);
}

/* ── Empty state ── */
.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 80px 20px;
  color: var(--text3);
}
.empty-state i {
  font-size: 56px;
  color: #c5d0e0;
  display: block;
  margin-bottom: 16px;
}
.empty-state p {
  font-size: 16px;
  font-weight: 500;
}

/* ── Back FAB ── */
.fab {
  position: fixed;
  bottom: 28px;
  right: 28px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--navy);
  color: #fff;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
  padding: 12px 22px;
  border-radius: 50px;
  box-shadow: 0 4px 18px rgba(10,37,64,.3);
  transition: .2s;
}
.fab:hover {
  background: var(--blue2);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(10,37,64,.35);
}

/* ── Responsive ── */
@media (max-width: 640px) {
  .topbar { padding: 0 18px; }
  .wrapper { padding: 20px 16px 80px; }
  .brand-sep, .brand-sub { display: none; }
  .stats-row { display: none; }
}
</style>
</head>
<body>

<?php
// Stats calculate
$total_stock = 0;
$low_count   = 0;
$out_count   = 0;
foreach($equipments as $eq) {
  $s = (int)($eq['stock'] ?? 0);
  $total_stock += $s;
  if($s === 0)         $out_count++;
  elseif($s <= 5)      $low_count++;
}
?>

<!-- Topbar -->
<div class="topbar">
  <div class="topbar-left">
    <div class="brand">ANIFCO</div>
    <div class="brand-sep"></div>
    <div class="brand-sub">Equipment Manager</div>
  </div>
  <div class="topbar-right">
    <a href="upload_equipment.php" class="nav-btn solid">
      <i class="ti ti-upload"></i> Upload
    </a>
    <a href="update_stock.php" class="nav-btn outline">
      <i class="ti ti-refresh"></i> Update Stock
    </a>
    <a href="dashboard.php" class="nav-btn outline">
      <i class="ti ti-layout-dashboard"></i> Dashboard
    </a>
  </div>
</div>

<!-- Page wrapper -->
<div class="wrapper">

  <!-- Page head -->
  <div class="page-head">
    <div class="page-head-left">
      <h1>Equipments</h1>
      <p><?php echo $total; ?> items in inventory</p>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="ti ti-packages"></i></div>
      <div class="stat-info"><label>Total Items</label><strong><?php echo $total; ?></strong></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="ti ti-stack"></i></div>
      <div class="stat-info"><label>Total Stock</label><strong><?php echo $total_stock; ?></strong></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon amber"><i class="ti ti-alert-triangle"></i></div>
      <div class="stat-info"><label>Low Stock</label><strong><?php echo $low_count; ?></strong></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#fef2f2;color:#dc2626;"><i class="ti ti-x"></i></div>
      <div class="stat-info"><label>Out of Stock</label><strong><?php echo $out_count; ?></strong></div>
    </div>
  </div>

  <!-- Toolbar -->
  <div class="toolbar">
    <div class="search-box">
      <i class="ti ti-search"></i>
      <input type="text" id="searchInput" placeholder="Search by name or company…" oninput="filterCards()">
    </div>
    <button class="filter-btn" id="filterAll"    onclick="setFilter('all')"  >All</button>
    <button class="filter-btn" id="filterLow"    onclick="setFilter('low')"  ><i class="ti ti-alert-triangle"></i> Low</button>
    <button class="filter-btn" id="filterOut"    onclick="setFilter('out')"  ><i class="ti ti-x"></i> Out</button>
  </div>

  <!-- Grid -->
  <div class="grid" id="cardGrid">

    <?php if($total === 0): ?>
      <div class="empty-state">
        <i class="ti ti-package-off"></i>
        <p>No equipment found. Upload one to get started.</p>
      </div>
    <?php else: ?>
      <?php foreach($equipments as $eq):
        $stock = (int)($eq['stock'] ?? 0);
        if($stock === 0)       { $ribbon = 'out';  $ribbon_label = 'Out of Stock'; $ribbon_icon = 'ti-x'; }
        elseif($stock <= 5)    { $ribbon = 'low';  $ribbon_label = 'Low Stock';    $ribbon_icon = 'ti-alert-triangle'; }
        else                   { $ribbon = 'in';   $ribbon_label = 'In Stock';     $ribbon_icon = 'ti-check'; }
      ?>
      <div class="eq-card" data-name="<?php echo strtolower(htmlspecialchars($eq['name'] ?? '')); ?>" data-company="<?php echo strtolower(htmlspecialchars($eq['company'] ?? '')); ?>" data-stock="<?php echo $ribbon; ?>">

        <div class="eq-img-wrap">
          <?php if(!empty($eq['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($eq['image']); ?>" alt="<?php echo htmlspecialchars($eq['name'] ?? ''); ?>" loading="lazy">
          <?php else: ?>
            <div class="no-img"><i class="ti ti-photo-off"></i></div>
          <?php endif; ?>
          <div class="stock-ribbon <?php echo $ribbon; ?>">
            <i class="ti <?php echo $ribbon_icon; ?>" style="font-size:10px;"></i>
            <?php echo $ribbon_label; ?>
          </div>
        </div>

        <div class="eq-body">
          <div class="eq-name"><?php echo htmlspecialchars($eq['name'] ?? 'Unnamed'); ?></div>
          <div class="eq-company">
            <i class="ti ti-building" style="font-size:14px; color:#a0aec0;"></i>
            <?php echo htmlspecialchars($eq['company'] ?? '—'); ?>
          </div>
          <div class="eq-footer">
            <div class="stock-count">Stock: <span><?php echo $stock; ?></span></div>
            <i class="ti ti-chevron-right" style="color:#c5d0e0; font-size:18px;"></i>
          </div>
        </div>

      </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div><!-- /grid -->

</div><!-- /wrapper -->

<!-- Back FAB -->
<a href="dashboard.php" class="fab">
  <i class="ti ti-arrow-left"></i> Back
</a>

<script>
let currentFilter = 'all';

function filterCards() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('.eq-card').forEach(card => {
    const matchSearch = card.dataset.name.includes(q) || card.dataset.company.includes(q);
    const matchFilter = currentFilter === 'all' || card.dataset.stock === currentFilter;
    card.style.display = (matchSearch && matchFilter) ? '' : 'none';
  });
}

function setFilter(f) {
  currentFilter = f;
  ['All','Low','Out'].forEach(x => {
    const btn = document.getElementById('filter'+x);
    btn.classList.toggle('active', x.toLowerCase() === f || (f==='all' && x==='All'));
  });
  filterCards();
}

// default active
document.getElementById('filterAll').classList.add('active');
</script>

</body>
</html>