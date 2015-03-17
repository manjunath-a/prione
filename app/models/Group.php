<?php

class Group extends Eloquent {

	 /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'group';

    /**
     * Primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $guarded = array('id');

    public function user()
    {
        return $this->hasMany('Group');
    }
}
