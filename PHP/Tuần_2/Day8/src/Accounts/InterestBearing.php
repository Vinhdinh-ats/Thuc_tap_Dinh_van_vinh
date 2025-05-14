<?php
namespace XYZBank\Accounts;

/**
 * Interface for accounts that bear interest.
 */
interface InterestBearing {
    public function calculateAnnualInterest(): float;
}
?>