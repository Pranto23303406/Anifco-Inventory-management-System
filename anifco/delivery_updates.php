<?php include("db.php"); ?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delivery Updates</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: Arial, sans-serif;
    background: #f0f4f8;
    min-height: 100vh;
    padding: 30px 16px;
  }

  /* ── Top bar ── */
  .top-bar {
    max-width: 960px;
    margin: 0 auto 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
  }

  .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #1a73e8;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    background: #fff;
    border: 1px solid #d0dff7;
    border-radius: 8px;
    padding: 7px 16px;
    transition: background .15s;
  }
  .back-btn:hover { background: #eff6ff; }

  .page-title {
    font-size: 20px;
    font-weight: 700;
    color: #1a202c;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* ── Card ── */
  .card {
    max-width: 960px;
    margin: 0 auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.08);
    overflow: hidden;
  }

  /* ── Card header ── */
  .card-header {
    background: linear-gradient(135deg, #0f4c75 0%, #1a73e8 100%);
    padding: 22px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
  }

  .card-header h2 {
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .total-badge {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.35);
    color: #fff;
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 13px;
    font-weight: 500;
  }

  /* ── Search bar ── */
  .search-wrap {
    padding: 16px 24px;
    border-bottom: 1px solid #e8edf3;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .search-wrap i {
    color: #a0aec0;
    font-size: 18px;
  }

  .search-wrap input {
    border: none;
    outline: none;
    font-size: 14px;
    color: #2d3748;
    width: 100%;
    background: transparent;
  }

  .search-wrap input::placeholder { color: #a0aec0; }

  /* ── Table ── */
  .table-wrap { overflow-x: auto; }

  table {
    width: 100%;
    border-collapse: collapse;
    min-width: 560px;
  }

  thead tr {
    background: #f7fafc;
    border-bottom: 2px solid #e2e8f0;
  }

  th {
    padding: 13px 18px;
    font-size: 12px;
    font-weight: 700;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: .5px;
    text-align: left;
    white-space: nowrap;
  }

  td {
    padding: 14px 18px;
    font-size: 14px;
    color: #2d3748;
    text-align: left;
    border-bottom: 1px solid #f0f4f8;
  }

  tbody tr:last-child td { border-bottom: none; }

  tbody tr {
    transition: background .12s;
  }
  tbody tr:hover { background: #f8faff; }

  /* order id */
  .order-id {
    font-weight: 600;
    color: #1a73e8;
    font-family: monospace;
    font-size: 13px;
  }

  /* company name */
  .company {
    display: flex;
    align-items: center;
    gap: 9px;
  }

  .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #dbeafe;
    color: #1a73e8;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  /* status badge */
  .badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
  }

  .badge.delivered   { background: #f0fff4; color: #276749; }
  .badge.processing  { background: #fffbeb; color: #92400e; }
  .badge.shipped     { background: #e0f2fe; color: #075985; }
  .badge.pending     { background: #f5f0ff; color: #553c9a; }
  .badge.cancelled   { background: #fff5f5; color: #9b2c2c; }
  .badge.default     { background: #f0f4f8; color: #4a5568; }

  /* action button */
  .view-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #1a73e8;
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    padding: 7px 16px;
    border-radius: 8px;
    text-decoration: none;
    transition: background .15s, transform .1s;
  }
  .view-btn:hover { background: #1558b0; transform: translateY(-1px); }

  /* empty state */
  .empty {
    text-align: center;
    padding: 60px 20px;
    color: #718096;
  }
  .empty i { font-size: 48px; color: #cbd5e0; display: block; margin-bottom: 12px; }

  /* footer */
  .card-footer {
    background: #f7fafc;
    border-top: 1px solid #e8edf3;
    padding: 12px 24px;
    font-size: 13px;
    color: #718096;
  }
</style>
</head>
<body>

<?php
$result  = $conn->query("SELECT * FROM invoices ORDER BY id DESC");
$rows    = [];
while($r = $result->fetch_assoc()) $rows[] = $r;
$total   = count($rows);
?>

<!-- Top bar -->
<div class="top-bar">
  <a href="javascript:history.back()" class="back-btn">
    <i class="ti ti-arrow-left"></i> Back
  </a>
  <div class="page-title">
    <i class="ti ti-truck" style="color:#1a73e8;"></i>
    Delivery Updates
  </div>
  <div style="width:80px;"></div><!-- spacer for centering -->
</div>

<!-- Card -->
<div class="card">

  <!-- Card header -->
  <div class="card-header">
    <h2>
      <i class="ti ti-list-details"></i>
      All Orders
    </h2>
    <div class="total-badge">
      <i class="ti ti-packages" style="font-size:13px; vertical-align:-2px;"></i>
      <?php echo $total; ?> orders
    </div>
  </div>

  <!-- Search -->
  <div class="search-wrap">
    <i class="ti ti-search"></i>
    <input type="text" id="searchInput" placeholder="Search by Order ID or Company Name…" oninput="filterTable()">
  </div>

  <!-- Table -->
  <div class="table-wrap">
    <table id="deliveryTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Order ID</th>
          <th>Company Name</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if($total === 0): ?>
          <tr><td colspan="5">
            <div class="empty">
              <i class="ti ti-package-off"></i>
              No delivery records found.
            </div>
          </td></tr>
        <?php else: ?>
          <?php foreach($rows as $i => $row):
            $status = strtolower(trim($row['status'] ?? ''));
            if(strpos($status,'delivered') !== false)       $badge = 'delivered';
            elseif(strpos($status,'process') !== false)     $badge = 'processing';
            elseif(strpos($status,'ship') !== false)        $badge = 'shipped';
            elseif(strpos($status,'pending') !== false)     $badge = 'pending';
            elseif(strpos($status,'cancel') !== false)      $badge = 'cancelled';
            else                                             $badge = 'default';

            $icons = [
              'delivered'  => 'ti-circle-check',
              'processing' => 'ti-settings',
              'shipped'    => 'ti-package',
              'pending'    => 'ti-clock',
              'cancelled'  => 'ti-x',
              'default'    => 'ti-point',
            ];

            $name   = htmlspecialchars($row['name'] ?? '');
            $initials = strtoupper(substr($name, 0, 1));
          ?>
          <tr>
            <td style="color:#a0aec0; font-size:13px;"><?php echo $i+1; ?></td>
            <td><span class="order-id">#<?php echo htmlspecialchars($row['order_id']); ?></span></td>
            <td>
              <div class="company">
                <div class="avatar"><?php echo $initials; ?></div>
                <?php echo $name; ?>
              </div>
            </td>
            <td>
              <span class="badge <?php echo $badge; ?>">
                <i class="ti <?php echo $icons[$badge]; ?>" style="font-size:11px;"></i>
                <?php echo htmlspecialchars($row['status'] ?? 'N/A'); ?>
              </span>
            </td>
            <td>
              <a class="view-btn" href="tracking_details.php?order_id=<?php echo urlencode($row['order_id']); ?>">
                <i class="ti ti-map-pin"></i> Track
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Footer -->
  <div class="card-footer">
    Showing <strong><?php echo $total; ?></strong> records
  </div>

</div>

<script>
function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const rows = document.querySelectorAll('#deliveryTable tbody tr');
  rows.forEach(row => {
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(q) ? '' : 'none';
  });
}
</script>

</body>
</html>