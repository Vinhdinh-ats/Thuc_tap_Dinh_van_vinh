<?php
class AffiliatePartner
{
     // Hằng số tên nền tảng
     const PLATFORM_NAME = "VietLink Affiliate";

     // Thuộc tính
     protected $name;
     protected $email;
     protected $commissionRate;
     protected $isActive;

     // Constructor
     public function __construct($name, $email, $commissionRate, $isActive = true)
     {
          $this->name = $name;
          $this->email = $email;
          $this->commissionRate = $commissionRate;
          $this->isActive = $isActive;
     }

     // Destructor
     public function __destruct()
     {
          echo "Affiliate Partner {$this->name} has been removed from memory.<br>";
     }

     // Tính hoa hồng
     public function calculateCommission($orderValue)
     {
          if (!$this->isActive) {
               return 0;
          }
          return ($this->commissionRate / 100) * $orderValue;
     }

     // Lấy thông tin tổng quan
     public function getSummary()
     {
          return "Partner: {$this->name} | Email: {$this->email} | Commission Rate: {$this->commissionRate}% | Active: " . ($this->isActive ? "Yes" : "No") . " | Platform: " . self::PLATFORM_NAME;
     }

     // Getters
     public function getName()
     {
          return $this->name;
     }

     public function getEmail()
     {
          return $this->email;
     }

     public function getCommissionRate()
     {
          return $this->commissionRate;
     }

     public function isActive()
     {
          return $this->isActive;
     }
}
