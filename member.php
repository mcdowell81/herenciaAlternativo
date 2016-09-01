<?php

class Member 
{
	private $name;
    private $parent;
    protected $birthdate;
    private $holdings = [];

	public function __construct($name, $holdings, $parent = null, DateTime $birthdate) 
	{
        $this->name = $name;
        $this->holdings = $holdings;
        $this->parent = $parent;
        $this->birthdate = $birthdate;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getHoldings()
    {
        return $this->holdings;
    }

    public function resetHoldings()
    {
        $this->holdings = [];
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function isCentenary()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"));
        
        $date = strtotime(date("Y-m-d H:i:s") . ' -100 year');
        $hundredYearsAgo = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', $date));

        return ( $hundredYearsAgo >= $this->birthdate ) ? true : false;
    }

    /**
     * @param array $options must ( holdingType | units )
     */
    public function toInherit($options)
    {
        $this->holdings[$options['holdingType']] = $this->holdings[$options['holdingType']] += $options['units'];

    }

}