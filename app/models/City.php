<?php

class City extends Eloquent {

	/**
	 * Get the comment's content.
	 *
	 * @return string
	 */
	public function cityName()
	{
		return $this->cityName;
	}

	/**
	 * Get the comment's author.
	 *
	 * @return User
	 */
	public function status()
	{
		return $this->status;
	}

	/**
	 * Get the comment's post's.
	 *
	 * @return Blog\Post
	 */
	public function sort()
	{
		return $this->sort;
	}

}
