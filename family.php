<?php

require_once 'member.php';

class Family 
{
	private $members = [];
	protected $name;			//		<----- protected ???

	public function __construct($name) 
	{
        $this->name = $name;
    }

    public function addMember($member)
    {
    	array_push($this->members, $member);
    }

    public function getMembers()
    {
    	return $this->members;
    }

    public function getMember($name)
    {
        foreach ($this->members as $member) {
            if ( $member->getName() === $name ) {
                return $member;
            }
        }
        return null;
    }

    public function getMemberChildrens($name)
    {
        $childrens = [];

        foreach ($this->members as $member) {
            if ( $member->getParent() === $name ) {
                array_push($childrens, $member);
            }
        }
        return $childrens;

    }

    public function getMemberFirstBorn($name)
    {
        $sortChildrens = $this->getMemberChildrens($name);
        uasort($sortChildrens, function($a, $b) {
            return $a->getBirthdate() >= $b->getBirthdate() ? $a : $b;
        });

        return current($sortChildrens);
    }

    /**
     * @param Member $member
     */
    public function makeGrant($member)
    {
        $holdings = $member->getHoldings();

        $childrens = $this->getMemberChildrens($member->getName());

        foreach ($holdings as $key => $value) {
            
            switch ($key) {
                case MONEYHOLDING:
                    $this->makeMoneyGrant([
                        'money' => $value,
                        'childrens' => $childrens
                    ]);
                    break;

                case GROUNDHOLDING:
                    $this->makeGroundGrant([
                        'grounds' => $value,
                        'member' => $member
                    ]);
                    break;

                case PROPERTYHOLDING:
                    $this->makePropertyGrant([
                        'properties' => $value,
                        'childrens' => $childrens
                    ]);
                    break;
                
                default:
                    $this->makeMoneyGrant([
                        'holdingUnits' => $value,
                        'childrens' => $childrens
                    ]);
                    break;
            }
        }

        $member->resetHoldings();
    }

    /**
     * @param array $options must ( money | childrens )
     */
    private function makeMoneyGrant($options)
    {
        $childrens = $options['childrens'];

        if ( is_null($childrens) ) {
            return false;
        }

        $childrenGrantPartition = (int) ($options['money'] / count($childrens));

        foreach ($childrens as $children) {
            $childrenChildrens = $this->getMemberChildrens($children->getName());

            if ( count($childrenChildrens) > 0 ) {
                
                $children->toInherit([
                    'units' => (int) ($childrenGrantPartition / 2),
                    'holdingType' => MONEYHOLDING
                ]);
                
                $this->makeMoneyGrant([
                    'money' => (int) $childrenGrantPartition / 2,
                    'childrens' => $childrenChildrens
                ]);
            } else {
                $children->toInherit([
                    'units' => $childrenGrantPartition,
                    'holdingType' => MONEYHOLDING
                ]);
            }
        }

        return true;
    }

    /**
     * @param array $options must ( grounds | member )
     */
    private function makeGroundGrant($options)
    {
        $member = $options['member'];
        
        $firstBorn = $this->getMemberFirstBorn($member->getName());
        if ( ! empty($firstBorn) ) {
            $firstBorn->toInherit([
                'units' => $options['grounds'],
                'holdingType' => GROUNDHOLDING
            ]);

            return true;

        } else {

            return false;

        }

    }

    /**
     * @param array $options must ( properties | childrens )
     */
    private function makePropertyGrant($options)
    {
        $childrens = $options['childrens'];

        if ( is_null($childrens) ) {
            return false;
        }

        $childrensArrayPos = 0;
        $childernsArrayCount = count($childrens);
        for ($i=0; $i < $options['properties']; $i++) { 
            $childrens[$childrensArrayPos]->toInherit([
                'units' => 1,
                'holdingType' => PROPERTYHOLDING
            ]);

            $childrensArrayPos++;
            if ( $childrensArrayPos == $childernsArrayCount ){
                $childrensArrayPos = 0;
            }
        }

        return true;

    }
}