<?php

class SalesChannel extends Eloquent {

	 /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_channel';

    /**
     * Primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $guarded = array('id');

   /**
	 * Get the channelName content.
	 *
	 * @return string
	 */
	public function channelName()
	{
		return $this->channelName;
	}

	/**
	 * Get the status .
	 *
	 * @return integer
	 */
	public function status()
	{
		return $this->status;
	}

	/**
	 * Get the sort .
	 *
	 * @return integer
	 */
	public function sort()
	{
		return $this->sort;
	}

}
