<?php

class ReportController extends BaseController
{
  /**
   * construct the models.
   */
  public function __construct()
  {
      //      parent::__construct();
  }

    /**
     * Returns report.
     *
     * @return View
     */
    public function getIndex()
    {
        //
    }

    /**
     * Returns admin report.
     *
     * @return View
     */
    public function getAdmin()
    {
        // Show the page

        return View::make('site/report/admin');
          //  ->with('ticketid',$ticket);
    }
}
