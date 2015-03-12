<?php

class City extends Eloquent {

	 /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'city';

    /**
     * Primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $guarded = array('id');

   /**
	 * Get the city name.
	 *
	 * @return string
	 */
	public function cityName()
	{
		return $this->cityName;
	}

	/**
	 * Get the status.
	 *
	 * @return intger
	 */
	public function status()
	{
		return $this->status;
	}

	/**
	 * Get the sort.
	 *
	 * @return Integer
	 */
	public function sort()
	{
		return $this->sort;
	}

}
