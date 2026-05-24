<?php include("db.php"); ?>

<?php
$order_id = $_GET['order_id'];

// Order basic info আনো (orders table থেকে)
$order_info = $conn->query("SELECT * FROM orders WHERE id='$order_id' LIMIT 1");
$order = $order_info->fetch_assoc();

// Tracking steps আনো
$result = $conn->query("SELECT * FROM order_tracking 
    WHERE order_id='$order_id' ORDER BY id ASC");

// সব steps array-তে নাও
$steps = [];
while($row = $result->fetch_assoc()){
    $steps[] = $row;
}

// শেষ step = current active status
$total = count($steps);

// Status অনুযায়ী icon ও description
function getStepMeta($status) {
    $status_lower = strtolower(trim($status));
    $map = [
        'order placed'    => ['icon' => 'ti-receipt',       'desc' => 'আপনার অর্ডার সফলভাবে গ্রহণ করা হয়েছে।'],
        'processing'      => ['icon' => 'ti-settings',      'desc' => 'অর্ডারটি প্রক্রিয়াকরণ চলছে।'],
        'confirmed'       => ['icon' => 'ti-circle-check',  'desc' => 'অর্ডারটি নিশ্চিত করা হয়েছে।'],
        'shipped'         => ['icon' => 'ti-package',       'desc' => 'পণ্যটি শিপমেন্টে পাঠানো হয়েছে।'],
        'out for delivery'=> ['icon' => 'ti-truck',         'desc' => 'পণ্যটি ডেলিভারির পথে আছে।'],
        'delivered'       => ['icon' => 'ti-home-check',    'desc' => 'পণ্যটি সফলভাবে পৌঁছে দেওয়া হয়েছে।'],
        'cancelled'       => ['icon' => 'ti-x',             'desc' => 'অর্ডারটি বাতিল করা হয়েছে।'],
    ];
    return isset($map[$status_lower]) ? $map[$status_lower] : ['icon' => 'ti-point', 'desc' => ''];
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Tracking #<?php echo htmlspecialchars($order_id); ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: Arial, sans-serif;
    background: #f0f4f8;
    min-height: 100vh;
    padding: 30px 16px;
  }

  .card {
    max-width: 580px;
    margin: 0 auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.08);
    overflow: hidden;
  }

  /* ── Header ── */
  .card-header {
    background: linear-gradient(135deg, #1a73e8 0%, #0d5db5 100%);
    padding: 24px 28px;
    color: #fff;
  }

  .card-header h2 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .meta-chip {
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 12px;
    color: #fff;
  }

  .meta-chip span {
    font-weight: 600;
  }

  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.22);
    border: 1px solid rgba(255,255,255,0.4);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 600;
    color: #fff;
    margin-top: 12px;
  }

  /* ── Timeline body ── */
  .card-body {
    padding: 28px 28px 32px;
  }

  .timeline {
    position: relative;
    padding-left: 36px;
  }

  /* vertical line */
  .timeline::before {
    content: '';
    position: absolute;
    left: 13px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: #e2e8f0;
    border-radius: 2px;
  }

  .step {
    position: relative;
    margin-bottom: 28px;
  }
  .step:last-child { margin-bottom: 0; }

  /* dot */
  .dot {
    position: absolute;
    left: -36px;
    top: 2px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #cbd5e0;
    background: #fff;
    z-index: 1;
    font-size: 13px;
    transition: all .2s;
  }

  .dot.done {
    background: #1a73e8;
    border-color: #1a73e8;
    color: #fff;
  }

  .dot.active {
    background: #fff;
    border-color: #1a73e8;
    color: #1a73e8;
    box-shadow: 0 0 0 4px rgba(26,115,232,0.12);
  }

  .dot.cancelled-dot {
    background: #e53e3e;
    border-color: #e53e3e;
    color: #fff;
  }

  /* step content */
  .step-inner {
    background: #f8fafc;
    border: 1px solid #e8edf3;
    border-radius: 10px;
    padding: 12px 16px;
    transition: box-shadow .2s;
  }

  .step-inner:hover {
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
  }

  .step-inner.active-step {
    background: #eff6ff;
    border-color: rgba(26,115,232,0.3);
  }

  .step-inner.cancelled-step {
    background: #fff5f5;
    border-color: rgba(229,62,62,0.3);
  }

  .step-title {
    font-size: 15px;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 3px;
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .step-title.active-title { color: #1a73e8; }
  .step-title.cancelled-title { color: #e53e3e; }

  .step-time {
    font-size: 12px;
    color: #718096;
    margin-bottom: 6px;
  }

  .step-desc {
    font-size: 13px;
    color: #4a5568;
  }

  /* no data */
  .no-data {
    text-align: center;
    color: #718096;
    padding: 40px 0;
    font-size: 15px;
  }

  .no-data i {
    font-size: 40px;
    color: #cbd5e0;
    display: block;
    margin-bottom: 12px;
  }

  /* back button */
  .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin: 0 0 20px 0;
    color: #1a73e8;
    font-size: 14px;
    text-decoration: none;
    font-weight: 500;
  }

  .back-btn:hover { text-decoration: underline; }

  @media (max-width: 480px) {
    .card-header, .card-body { padding: 20px 18px; }
    .timeline { padding-left: 30px; }
    .dot { left: -30px; }
  }
</style>
</head>
<body>

<div style="max-width:580px; margin:0 auto;">
  <a href="javascript:history.back()" class="back-btn">
    <i class="ti ti-arrow-left"></i> Back
  </a>
</div>

<div class="card">

  <!-- Header -->
  <div class="card-header">
    <h2>
      <i class="ti ti-map-pin"></i>
      Order Tracking
    </h2>

    <div class="meta-row">
      <div class="meta-chip">Order ID: <span>#<?php echo htmlspecialchars($order_id); ?></span></div>
      <?php if($order): ?>
        <?php if(!empty($order['created_at'])): ?>
          <div class="meta-chip">Placed: <span><?php echo date('d M Y', strtotime($order['created_at'])); ?></span></div>
        <?php endif; ?>
        <?php if(!empty($order['customer_name'])): ?>
          <div class="meta-chip">Customer: <span><?php echo htmlspecialchars($order['customer_name']); ?></span></div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <?php if($total > 0): ?>
      <?php $last = end($steps); ?>
      <div class="status-pill">
        <i class="ti ti-circle-filled" style="font-size:8px;"></i>
        <?php echo htmlspecialchars($last['status']); ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Body / Timeline -->
  <div class="card-body">
    <?php if($total == 0): ?>
      <div class="no-data">
        <i class="ti ti-package-off"></i>
        No tracking data found for this order.
      </div>

    <?php else: ?>
      <div class="timeline">
        <?php foreach($steps as $i => $row):
          $is_last    = ($i === $total - 1);
          $is_cancel  = strtolower(trim($row['status'])) === 'cancelled';
          $meta       = getStepMeta($row['status']);

          // dot class
          if($is_cancel)      $dot_class = 'dot cancelled-dot';
          elseif($is_last)    $dot_class = 'dot active';
          else                $dot_class = 'dot done';

          // inner class
          if($is_cancel)      $inner_class = 'step-inner cancelled-step';
          elseif($is_last)    $inner_class = 'step-inner active-step';
          else                $inner_class = 'step-inner';

          // title class
          if($is_cancel)      $title_class = 'step-title cancelled-title';
          elseif($is_last)    $title_class = 'step-title active-title';
          else                $title_class = 'step-title';
        ?>

        <div class="step">
          <div class="<?php echo $dot_class; ?>">
            <?php if(!$is_last || $is_cancel): ?>
              <i class="ti ti-check"></i>
            <?php else: ?>
              <i class="ti <?php echo $meta['icon']; ?>"></i>
            <?php endif; ?>
          </div>

          <div class="<?php echo $inner_class; ?>">
            <div class="<?php echo $title_class; ?>">
              <i class="ti <?php echo $meta['icon']; ?>"></i>
              <?php echo htmlspecialchars($row['status']); ?>
            </div>
            <div class="step-time">
              <i class="ti ti-clock" style="font-size:11px; vertical-align:-1px;"></i>
              <?php
                // updated_at বা created_at যেটা আছে
                $ts = !empty($row['updated_at']) ? $row['updated_at'] : $row['created_at'] ?? '';
                echo htmlspecialchars($ts);
              ?>
            </div>
            <?php if(!empty($meta['desc'])): ?>
              <div class="step-desc"><?php echo $meta['desc']; ?></div>
            <?php endif; ?>
            <?php if(!empty($row['note'])): ?>
              <div class="step-desc" style="margin-top:4px; color:#2d6a4f;">
                <i class="ti ti-message-circle" style="font-size:12px;"></i>
                <?php echo htmlspecialchars($row['note']); ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

</body>
</html>