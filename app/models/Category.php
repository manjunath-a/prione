<?php

class Category extends Eloquent {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'category';

  /**
   * Primary key for the table.
   *
   * @var string
   */
  protected $primaryKey = 'id';
  protected $guarded = array('id');

  /**
	 * Get the categoryName  content.
	 *
	 * @return string
	 */
	public function categoryName()
	{
		return $this->categoryName;
	}

	/**
	 * Get the status .
	 *
	 * @return boolean
	 */
	public function status()
	{
		return $this->status;
	}

	/**
	 * Get the sort's .
	 *
	 * @return integer
	 */
	public function sort()
	{
		return $this->sort;
	}

}
