<?php

require_once 'family.php';
require_once 'member.php';

define('MONEYHOLDING', 1);
define('GROUNDHOLDING', 2);
define('PROPERTYHOLDING', 3);

class Api 
{
	private $families = [];

    public function createFamily($name)
    {
    	$family = new Family($name);
    	return $family;
    }

    public function addMemberToFamily(Family $family, $name, $holdings, $parent = null, DateTime $birthdate)
    {
        $member = new Member($name, $holdings, $parent, $birthdate);
        $family->addMember($member);
    }

    public function getFamilyByName($name)
    {
        foreach ($families as $family) {
            if ( $family->name == $name ) {
                return $family;
            }
        }
        return false;
    }

    public function getFamilyMembers(Family $family)
    {  
        return $family->getMembers();
    }

    public function getHeritageByName($name, Family $family, DateTime $currentDate)
    {
        $member = $family->getMember($name);
        $holdingValue = $this->getHoldingValue($member->getHoldings());

        if ($member->isCentenary()) {
            $family->makeGrant($member);
        }

        return $holdingValue;
    }

    public function getHeritage(Family $family, DateTime $currentDate)
    {
        $totalAmount = 0;
        $members = $this->getFamilyMembers($family);

        foreach ($members as $member) {
            if ($member->isCentenary()) {
                $family->makeGrant($member);
            }
            $holdingValue = $this->getHoldingValue($member->getHoldings());
            $totalAmount += $holdingValue;
        }
        
        return $totalAmount;
    }

    /**
     * PRIVATE FUNCTIONS
     */


    private function getHoldingValue($holdings)
    {
        $holdingValue = 0;
        foreach ($holdings as $key => $value) {
            switch ($key) {
                case MONEYHOLDING:
                    $holdingValue += (int)$value;
                    break;

                case GROUNDHOLDING:
                    $holdingValue += (int)$value * 300;
                    break;

                case PROPERTYHOLDING:
                    $holdingValue += (int)$value * 1000000;
                    break;
            }
        }

        return $holdingValue;
    }

}