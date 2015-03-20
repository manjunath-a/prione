<?php

class Status extends Eloquent {

	 /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'status';

    /**
     * Primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $guarded = array('id');

    public function ticketTransaction()
    {
        return $this->hasMany('Status');
    }
}
