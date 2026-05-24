<?php
session_start();
include("db.php");

// Login check
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// User info আনো (company_name দরকার invoices match করতে)
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$company_name = $user['company_name'] ?? '';

// Search
$searched   = false;
$steps      = [];
$order_data = null;
$error      = '';
$my_orders  = [];

// এই user-এর সব orders আনো (latest 10)
$stmt2 = $conn->prepare("SELECT * FROM invoices WHERE name = ? ORDER BY id DESC LIMIT 10");
$stmt2->bind_param("s", $company_name);
$stmt2->execute();
$ord_res = $stmt2->get_result();
while($r = $ord_res->fetch_assoc()) $my_orders[] = $r;

// Search submit হলে
if(isset($_GET['order_id']) && trim($_GET['order_id']) !== '') {
    $searched  = true;
    $order_id  = trim($_GET['order_id']);

    // Invoice check — must belong to this user's company
    $stmt3 = $conn->prepare("SELECT * FROM invoices WHERE order_id = ? AND name = ? LIMIT 1");
    $stmt3->bind_param("ss", $order_id, $company_name);
    $stmt3->execute();
    $order_data = $stmt3->get_result()->fetch_assoc();

    if(!$order_data) {
        $error = 'Order ID <strong>' . htmlspecialchars($order_id) . '</strong> পাওয়া যায়নি বা এটি আপনার অর্ডার নয়।';
    } else {
        // Tracking steps
        $stmt4 = $conn->prepare("SELECT * FROM order_tracking WHERE order_id = ? ORDER BY id ASC");
        $stmt4->bind_param("s", $order_id);
        $stmt4->execute();
        $res4 = $stmt4->get_result();
        while($row = $res4->fetch_assoc()) $steps[] = $row;
    }
}

function stepMeta($status) {
    $map = [
        'order placed'     => ['ti-receipt',      '#1a73e8', 'আপনার অর্ডার সফলভাবে গ্রহণ করা হয়েছে।'],
        'confirmed'        => ['ti-circle-check',  '#0e9f6e', 'অর্ডারটি নিশ্চিত করা হয়েছে।'],
        'processing'       => ['ti-settings',      '#d97706', 'অর্ডার প্রক্রিয়াকরণ চলছে।'],
        'shipped'          => ['ti-package',       '#7c3aed', 'পণ্য শিপমেন্টে পাঠানো হয়েছে।'],
        'out for delivery' => ['ti-truck',         '#1a73e8', 'পণ্য ডেলিভারির পথে আছে।'],
        'delivered'        => ['ti-home-check',    '#0e9f6e', 'পণ্য সফলভাবে পৌঁছে দেওয়া হয়েছে। ধন্যবাদ!'],
        'cancelled'        => ['ti-x',             '#dc2626', 'দুঃখিত, অর্ডারটি বাতিল করা হয়েছে।'],
    ];
    return $map[strtolower(trim($status))] ?? ['ti-point', '#8a98ac', ''];
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Tracking — ANIFCO</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
:root {
  --brown:  #3d2b1f;
  --amber:  #c8870a;
  --amber2: #a06c07;
  --ambg:   #fdf6e3;
  --blue:   #1a73e8;
  --blue2:  #0d5db5;
  --sky:    #e8f1fd;
  --green:  #0e9f6e;
  --gbg:    #ecfdf5;
  --red:    #dc2626;
  --rbg:    #fef2f2;
  --border: #e8e0d5;
  --surf:   #f9f6f1;
  --card:   #fff;
  --t1:     #1c1208;
  --t2:     #5a4a38;
  --t3:     #9a8a78;
  --r:      14px;
  --sh:     0 2px 16px rgba(60,40,20,.07);
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--surf);
  min-height: 100vh;
  color: var(--t1);
}

/* ── Topbar (matches dashboard01 style) ── */
.topbar {
  background: var(--brown);
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 32px;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 12px rgba(0,0,0,.18);
}

.brand-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
}

.brand-icon {
  width: 36px; height: 36px;
  background: var(--amber);
  border-radius: 8px;
  display: flex; align-items:center; justify-content:center;
  font-size: 18px; font-weight: 700; color: #fff;
}

.brand-name {
  font-size: 18px; font-weight: 700;
  color: #fff; letter-spacing: -.2px;
}
.brand-name span { color: var(--amber); }

.topbar-right {
  display: flex; align-items:center; gap:10px;
}

.user-chip {
  display: flex; align-items:center; gap:8px;
  background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.18);
  border-radius: 20px;
  padding: 5px 14px 5px 8px;
  color: rgba(255,255,255,.85);
  font-size: 13px; font-weight: 500;
}

.user-avatar {
  width: 26px; height: 26px;
  background: var(--amber);
  border-radius: 50%;
  display: flex; align-items:center; justify-content:center;
  font-size: 12px; font-weight: 700; color: #fff;
}

.back-btn {
  display: inline-flex; align-items:center; gap:6px;
  padding: 7px 16px; border-radius:8px;
  font-size: 13px; font-weight: 600;
  text-decoration: none;
  background: rgba(255,255,255,.1);
  color: rgba(255,255,255,.85);
  border: 1px solid rgba(255,255,255,.18);
  transition: .15s;
}
.back-btn:hover { background: rgba(255,255,255,.18); }

/* ── Wrapper ── */
.wrapper {
  max-width: 720px;
  margin: 0 auto;
  padding: 40px 20px 80px;
}

/* ── Hero ── */
.hero {
  text-align: center;
  margin-bottom: 36px;
}

.hero-icon {
  width: 64px; height: 64px;
  background: var(--ambg);
  border: 1.5px solid #e8d5a8;
  border-radius: 18px;
  display: flex; align-items:center; justify-content:center;
  font-size: 28px; color: var(--amber);
  margin: 0 auto 16px;
}

.hero h1 {
  font-size: 24px; font-weight: 700;
  letter-spacing: -.4px; margin-bottom: 6px;
}
.hero p { font-size: 14px; color: var(--t3); }

/* ── Search card ── */
.search-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--r);
  padding: 24px;
  box-shadow: var(--sh);
  margin-bottom: 28px;
}

.search-form {
  display: flex; gap:10px;
}

.search-wrap {
  flex: 1;
  display: flex; align-items:center; gap:10px;
  background: var(--surf);
  border: 1.5px solid var(--border);
  border-radius: 10px;
  padding: 0 16px;
  height: 50px;
  transition: .15s;
}
.search-wrap:focus-within {
  border-color: var(--amber);
  box-shadow: 0 0 0 3px rgba(200,135,10,.1);
  background: #fff;
}
.search-wrap i { color: var(--t3); font-size: 18px; flex-shrink:0; }
.search-wrap input {
  border:none; outline:none;
  font-family: 'DM Sans', sans-serif;
  font-size: 15px; font-weight: 500;
  color: var(--t1); width:100%; background:transparent;
}
.search-wrap input::placeholder { color:var(--t3); font-weight:400; }

.search-btn {
  display: inline-flex; align-items:center; gap:8px;
  height: 50px; padding: 0 26px;
  background: var(--amber); color:#fff;
  border:none; border-radius:10px;
  font-family:'DM Sans',sans-serif;
  font-size:14px; font-weight:700;
  cursor:pointer; transition:.15s;
  white-space:nowrap;
}
.search-btn:hover { background:var(--amber2); }

/* ── My orders quick-list ── */
.section-title {
  font-size: 13px; font-weight: 700;
  color: var(--t3); text-transform:uppercase;
  letter-spacing:.6px; margin-bottom:12px;
}

.orders-list {
  display: flex; flex-direction:column; gap:8px;
  margin-bottom: 28px;
}

.order-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 12px 16px;
  gap: 12px;
  flex-wrap: wrap;
  transition: box-shadow .15s;
}
.order-row:hover { box-shadow: 0 2px 10px rgba(60,40,20,.08); }

.or-left { display:flex; align-items:center; gap:12px; }

.or-icon {
  width: 38px; height:38px;
  background: var(--ambg);
  border-radius: 10px;
  display:flex; align-items:center; justify-content:center;
  font-size:18px; color:var(--amber); flex-shrink:0;
}

.or-id {
  font-family:'DM Mono',monospace;
  font-size:14px; font-weight:600; color:var(--t1);
}
.or-item { font-size:12px; color:var(--t3); margin-top:2px; }

.or-right { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }

.or-status {
  display:inline-flex; align-items:center; gap:5px;
  border-radius:20px; padding:4px 12px;
  font-size:11px; font-weight:700;
}
.or-status.delivered  { background:var(--gbg);  color:#065f46; }
.or-status.processing { background:#fffbeb;     color:#92400e; }
.or-status.shipped    { background:#eef2ff;     color:#3730a3; }
.or-status.pending    { background:#f5f0ff;     color:#6d28d9; }
.or-status.cancelled  { background:var(--rbg);  color:#991b1b; }
.or-status.default    { background:#f0f4f8;     color:#4a5a72; }

.track-link {
  display:inline-flex; align-items:center; gap:5px;
  background:var(--amber); color:#fff;
  text-decoration:none; font-size:12px; font-weight:600;
  padding:5px 14px; border-radius:7px;
  transition:.15s;
}
.track-link:hover { background:var(--amber2); }

/* ── Error ── */
.error-box {
  background:var(--rbg);
  border:1px solid rgba(220,38,38,.2);
  border-radius:var(--r);
  padding:18px 22px;
  display:flex; align-items:center; gap:12px;
  color:var(--red); font-size:14px;
  margin-bottom:24px;
  animation:fadeUp .25s ease;
}
.error-box i { font-size:22px; flex-shrink:0; }

/* ── Result ── */
.result-card {
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--r);
  box-shadow:var(--sh);
  overflow:hidden;
  animation:fadeUp .3s ease;
}

@keyframes fadeUp {
  from { opacity:0; transform:translateY(10px); }
  to   { opacity:1; transform:translateY(0); }
}

.result-head {
  background: linear-gradient(135deg, #3d2b1f, #5c3d28);
  padding: 20px 24px;
}

.rh-row {
  display:flex; align-items:center;
  justify-content:space-between;
  flex-wrap:wrap; gap:10px;
  margin-bottom:12px;
}

.rh-row h2 {
  font-size:16px; font-weight:600; color:#fff;
  display:flex; align-items:center; gap:8px;
}

.status-pill {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(255,255,255,.15);
  border:1px solid rgba(255,255,255,.25);
  color:#fff; border-radius:20px;
  padding:5px 14px; font-size:12px; font-weight:600;
}

.meta-chips { display:flex; flex-wrap:wrap; gap:8px; }
.meta-chip {
  background:rgba(255,255,255,.1);
  border:1px solid rgba(255,255,255,.2);
  color:rgba(255,255,255,.8);
  border-radius:20px; padding:4px 13px;
  font-size:12px;
}
.meta-chip span { font-weight:700; color:#fff; }

/* timeline */
.result-body { padding:26px 24px 30px; }

.timeline { position:relative; padding-left:38px; }
.timeline::before {
  content:''; position:absolute;
  left:13px; top:6px; bottom:6px;
  width:2px; background:var(--border); border-radius:2px;
}

.step { position:relative; margin-bottom:24px; }
.step:last-child { margin-bottom:0; }

.dot {
  position:absolute; left:-38px; top:2px;
  width:28px; height:28px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  border:2px solid var(--border);
  background:var(--card); z-index:1; font-size:13px;
}
.dot.done   { color:#fff; }
.dot.active { background:#fff; box-shadow:0 0 0 4px rgba(200,135,10,.15); }
.dot.cancel { color:#fff; }

.step-box {
  background:var(--surf);
  border:1px solid var(--border);
  border-radius:10px;
  padding:12px 16px;
  transition:box-shadow .15s;
}
.step-box:hover { box-shadow:0 2px 8px rgba(60,40,20,.08); }
.step-box.active-b { background:#fffbeb; border-color:rgba(200,135,10,.35); }
.step-box.cancel-b { background:var(--rbg); border-color:rgba(220,38,38,.25); }

.step-title {
  font-size:15px; font-weight:600;
  display:flex; align-items:center; gap:7px;
  margin-bottom:4px;
}
.step-time {
  font-size:12px; color:var(--t3);
  margin-bottom:5px;
  display:flex; align-items:center; gap:5px;
}
.step-desc { font-size:13px; color:var(--t2); }

.no-steps {
  text-align:center; padding:40px 0;
  color:var(--t3);
}
.no-steps i { font-size:40px; display:block; margin-bottom:10px; color:#ccc; }

@media(max-width:520px) {
  .topbar { padding:0 16px; }
  .search-form { flex-direction:column; }
  .search-btn { height:46px; }
  .wrapper { padding:24px 14px 60px; }
}
</style>
</head>
<body>

<!-- Topbar — same feel as dashboard01 -->
<div class="topbar">
  <div class="brand-wrap">
    <div class="brand-icon">A</div>
    <div class="brand-name">ANIF<span>CO</span></div>
  </div>
  <div class="topbar-right">
    <div class="user-chip">
      <div class="user-avatar"><?php echo strtoupper(substr($user_name,0,1)); ?></div>
      <?php echo htmlspecialchars($user_name); ?>
    </div>
    <a href="dashboard01.php" class="back-btn">
      <i class="ti ti-arrow-left"></i> Back
    </a>
  </div>
</div>

<div class="wrapper">

  <!-- Hero -->
  <div class="hero">
    <div class="hero-icon"><i class="ti ti-map-pin"></i></div>
    <h1>Order Tracking</h1>
    <p>আপনার Order ID দিয়ে ডেলিভারি স্ট্যাটাস দেখুন</p>
  </div>

  <!-- Search -->
  <div class="search-card">
    <form method="GET" class="search-form">
      <div class="search-wrap">
        <i class="ti ti-search"></i>
        <input
          type="text"
          name="order_id"
          placeholder="Order ID লিখুন… যেমন: ORD-2847"
          value="<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?>"
          autocomplete="off"
          autofocus
        >
      </div>
      <button type="submit" class="search-btn">
        <i class="ti ti-map-pin"></i> Track
      </button>
    </form>
  </div>

  <!-- Error -->
  <?php if($searched && $error): ?>
    <div class="error-box">
      <i class="ti ti-alert-circle"></i>
      <span><?php echo $error; ?></span>
    </div>
  <?php endif; ?>

  <!-- Tracking result -->
  <?php if($searched && $order_data): ?>
    <div class="result-card">
      <div class="result-head">
        <div class="rh-row">
          <h2><i class="ti ti-map-pin"></i> Tracking Details</h2>
          <?php if(count($steps) > 0):
            $last = end($steps);
            [$ic,$cl,$dc] = stepMeta($last['status']);
          ?>
            <div class="status-pill">
              <i class="ti ti-circle-filled" style="font-size:8px;"></i>
              <?php echo htmlspecialchars($last['status']); ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="meta-chips">
          <div class="meta-chip">Order: <span>#<?php echo htmlspecialchars($order_data['order_id']); ?></span></div>
          <?php if(!empty($order_data['item_name'])): ?>
            <div class="meta-chip">Item: <span><?php echo htmlspecialchars($order_data['item_name']); ?></span></div>
          <?php endif; ?>
          <?php if(!empty($order_data['grand_total'])): ?>
            <div class="meta-chip">Total: <span>৳<?php echo number_format((float)$order_data['grand_total'],2); ?></span></div>
          <?php endif; ?>
        </div>
      </div>

      <div class="result-body">
        <?php if(count($steps) === 0): ?>
          <div class="no-steps">
            <i class="ti ti-clock-off"></i>
            <p>এখনো কোনো tracking update নেই।</p>
          </div>
        <?php else: ?>
          <div class="timeline">
            <?php
            $total_s = count($steps);
            foreach($steps as $idx => $step):
              $is_last   = ($idx === $total_s - 1);
              $is_cancel = strtolower(trim($step['status'])) === 'cancelled';
              [$icon,$color,$sdesc] = stepMeta($step['status']);

              if($is_cancel) {
                $dot_cls = 'dot cancel';
                $box_cls = 'step-box cancel-b';
                $ttl_col = 'var(--red)';
                $dot_style = "background:#dc2626;border-color:#dc2626;";
              } elseif($is_last) {
                $dot_cls = 'dot active';
                $box_cls = 'step-box active-b';
                $ttl_col = 'var(--amber)';
                $dot_style = "border-color:$color;color:$color;";
              } else {
                $dot_cls = 'dot done';
                $box_cls = 'step-box';
                $ttl_col = 'var(--t1)';
                $dot_style = "background:$color;border-color:$color;";
              }

              $ts = !empty($step['updated_at']) ? $step['updated_at'] : ($step['created_at'] ?? '');
            ?>
            <div class="step">
              <div class="<?php echo $dot_cls; ?>" style="<?php echo $dot_style; ?>">
                <?php if(!$is_last || $is_cancel): ?>
                  <i class="ti ti-check"></i>
                <?php else: ?>
                  <i class="ti <?php echo $icon; ?>"></i>
                <?php endif; ?>
              </div>
              <div class="<?php echo $box_cls; ?>">
                <div class="step-title" style="color:<?php echo $ttl_col; ?>">
                  <i class="ti <?php echo $icon; ?>" style="font-size:15px;"></i>
                  <?php echo htmlspecialchars($step['status']); ?>
                </div>
                <?php if($ts): ?>
                  <div class="step-time">
                    <i class="ti ti-clock" style="font-size:11px;"></i>
                    <?php echo htmlspecialchars($ts); ?>
                  </div>
                <?php endif; ?>
                <?php if($sdesc): ?>
                  <div class="step-desc"><?php echo $sdesc; ?></div>
                <?php endif; ?>
                <?php if(!empty($step['note'])): ?>
                  <div class="step-desc" style="margin-top:5px;color:#065f46;">
                    <i class="ti ti-message-circle" style="font-size:12px;"></i>
                    <?php echo htmlspecialchars($step['note']); ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

  <!-- My recent orders (when not searched) -->
  <?php elseif(!$searched && count($my_orders) > 0): ?>
    <div class="section-title">আপনার সাম্প্রতিক অর্ডার</div>
    <div class="orders-list">
      <?php foreach($my_orders as $mo):
        $st = strtolower(trim($mo['status'] ?? ''));
        if(str_contains($st,'deliver'))     $sc='delivered';
        elseif(str_contains($st,'process')) $sc='processing';
        elseif(str_contains($st,'ship'))    $sc='shipped';
        elseif(str_contains($st,'cancel'))  $sc='cancelled';
        elseif(str_contains($st,'pending')) $sc='pending';
        else $sc='default';
        $s_icons=['delivered'=>'ti-home-check','processing'=>'ti-settings','shipped'=>'ti-package','cancelled'=>'ti-x','pending'=>'ti-clock','default'=>'ti-point'];
      ?>
      <div class="order-row">
        <div class="or-left">
          <div class="or-icon"><i class="ti ti-package"></i></div>
          <div>
            <div class="or-id">#<?php echo htmlspecialchars($mo['order_id']); ?></div>
            <div class="or-item"><?php echo htmlspecialchars($mo['item_name'] ?? ''); ?></div>
          </div>
        </div>
        <div class="or-right">
          <span class="or-status <?php echo $sc; ?>">
            <i class="ti <?php echo $s_icons[$sc]; ?>" style="font-size:10px;"></i>
            <?php echo htmlspecialchars($mo['status'] ?? 'N/A'); ?>
          </span>
          <a href="?order_id=<?php echo urlencode($mo['order_id']); ?>" class="track-link">
            <i class="ti ti-map-pin" style="font-size:12px;"></i> Track
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>
</body>
</html>