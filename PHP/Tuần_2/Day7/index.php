<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Affiliate Management</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
     <div class="container mt-5">
          <h1 class="mb-4">Affiliate Management System</h1>

          <!-- Form thêm cộng tác viên -->
          <h3>Add Affiliate Partner</h3>
          <form action="index.php" method="post" class="mb-5">
               <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
               </div>
               <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
               </div>
               <div class="mb-3">
                    <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                    <input type="number" step="0.1" class="form-control" id="commission_rate" name="commission_rate" required>
               </div>
               <div class="mb-3">
                    <label for="is_premium" class="form-label">Premium Partner</label>
                    <select class="form-select" id="is_premium" name="is_premium">
                         <option value="0">No</option>
                         <option value="1">Yes</option>
                    </select>
               </div>
               <div class="mb-3" id="bonus_field" style="display: none;">
                    <label for="bonus_per_order" class="form-label">Bonus per Order (VND)</label>
                    <input type="number" class="form-control" id="bonus_per_order" name="bonus_per_order" value="0">
               </div>
               <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">Active</label>
               </div>
               <button type="submit" class="btn btn-primary" name="add_partner">Add Partner</button>
          </form>

          <!-- Hiển thị danh sách cộng tác viên -->
          <h3>Current Affiliates</h3>
          <?php
          require_once 'includes/db_connect.php';
          require_once 'includes/AffiliateManager.php';

          $db = new Database();
          $conn = $db->getConnection();
          $manager = new AffiliateManager($conn);

          // Xử lý thêm cộng tác viên
          if (isset($_POST['add_partner'])) {
               $name = trim(htmlspecialchars($_POST['name']));
               $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
               $commissionRate = floatval($_POST['commission_rate']);
               $isActive = isset($_POST['is_active']);
               $isPremium = $_POST['is_premium'] == 1;
               $bonusPerOrder = $isPremium ? floatval($_POST['bonus_per_order']) : 0;

               if ($email && $commissionRate > 0) {
                    if ($isPremium) {
                         $partner = new PremiumAffiliatePartner($name, $email, $commissionRate, $bonusPerOrder, $isActive);
                    } else {
                         $partner = new AffiliatePartner($name, $email, $commissionRate, $isActive);
                    }
                    $manager->addPartner($partner);
                    echo '<div class="alert alert-success">Partner added successfully!</div>';
               } else {
                    echo '<div class="alert alert-danger">Invalid input data.</div>';
               }
          }

          // Hiển thị danh sách
          echo $manager->listPartners();

          // Tính tổng hoa hồng cho đơn hàng 2,000,000 VND
          echo "<h3>Commission for Order Value: 2,000,000 VND</h3>";
          $totalCommission = $manager->totalCommission(2000000);
          echo "<strong>Total Commission: " . number_format($totalCommission) . " VND</strong>";
          ?>
     </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
     <script>
          document.getElementById('is_premium').addEventListener('change', function() {
               document.getElementById('bonus_field').style.display = this.value == '1' ? 'block' : 'none';
          });
     </script>
</body>

</html>