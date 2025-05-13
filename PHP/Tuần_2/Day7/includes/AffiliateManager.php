<?php
require_once 'AffiliatePartner.php';
require_once 'PremiumAffiliatePartner.php';

class AffiliateManager
{
     private $partners = [];
     private $db;

     public function __construct($dbConnection)
     {
          $this->db = $dbConnection;
          $this->loadPartnersFromDB();
     }

     // Tải danh sách cộng tác viên từ database
     private function loadPartnersFromDB()
     {
          $stmt = $this->db->query("SELECT * FROM affiliates");
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
               if ($row['is_premium']) {
                    $partner = new PremiumAffiliatePartner(
                         $row['name'],
                         $row['email'],
                         $row['commission_rate'],
                         $row['bonus_per_order'],
                         $row['is_active']
                    );
               } else {
                    $partner = new AffiliatePartner(
                         $row['name'],
                         $row['email'],
                         $row['commission_rate'],
                         $row['is_active']
                    );
               }
               $this->partners[] = $partner;
          }
     }

     // Thêm cộng tác viên vào hệ thống và database
     public function addPartner($partner)
     {
          $this->partners[] = $partner;

          // Lưu vào database
          $stmt = $this->db->prepare("INSERT INTO affiliates (name, email, commission_rate, is_active, is_premium, bonus_per_order) VALUES (?, ?, ?, ?, ?, ?)");
          $isPremium = $partner instanceof PremiumAffiliatePartner;

          // Tính bonusPerOrder nếu là PremiumAffiliatePartner
          $bonus = 0;
          if ($isPremium) {
               // Với orderValue = 0, calculateCommission sẽ trả về chính bonusPerOrder
               $bonus = $partner->calculateCommission(0);
          }

          $stmt->execute([
               $partner->getName(),
               $partner->getEmail(),
               $partner->getCommissionRate(),
               $partner->isActive() ? 1 : 0,
               $isPremium ? 1 : 0,
               $bonus
          ]);
     }

     // Liệt kê thông tin cộng tác viên
     public function listPartners()
     {
          $output = "";
          foreach ($this->partners as $partner) {
               $output .= $partner->getSummary() . "<br>";
          }
          return $output;
     }

     // Tính tổng hoa hồng
     public function totalCommission($orderValue)
     {
          $total = 0;
          foreach ($this->partners as $partner) {
               $commission = $partner->calculateCommission($orderValue);
               $total += $commission;
               echo "Commission for {$partner->getName()}: " . number_format($commission) . " VND<br>";
          }
          return $total;
     }
}
