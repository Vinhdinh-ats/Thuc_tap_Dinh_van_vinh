<?php
require_once 'AffiliatePartner.php';

class PremiumAffiliatePartner extends AffiliatePartner
{
     private $bonusPerOrder;

     public function __construct($name, $email, $commissionRate, $bonusPerOrder, $isActive = true)
     {
          parent::__construct($name, $email, $commissionRate, $isActive);
          $this->bonusPerOrder = $bonusPerOrder;
     }

     // Override phương thức tính hoa hồng
     public function calculateCommission($orderValue)
     {
          if (!$this->isActive) {
               return 0;
          }
          $baseCommission = parent::calculateCommission($orderValue);
          return $baseCommission + $this->bonusPerOrder;
     }

     // Override phương thức getSummary để thêm thông tin bonus
     public function getSummary()
     {
          return parent::getSummary() . " | Bonus per Order: {$this->bonusPerOrder} VND";
     }
}
