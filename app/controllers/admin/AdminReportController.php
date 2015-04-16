<?php

class AdminReportController extends AdminController
{
    /**
     * Report Service
     * @var report
     */
    protected $report;

    /**
     * construct the models.
     */
    public function __construct()
    {
        parent::__construct();
        $this->report = App::make('report');
    }

  	/**
  	 * Returns report.
  	 *
  	 * @return View
  	 */
  	public function getIndex()
  	{
         return Redirect::to('admin/report/demand');
  	}

    /**
     * Returns admin report.
     *
     * @return View
     */
    public function getDemand()
    {
        $total_request = $this->report->getTotalRequest();
        $total_sku = $this->report->getTotalSKU();

        // Get By Stage
        $catalogCompleted   = Stage::where('stage_name', '(Central) Cataloging Completed')->first();
        $total_catlog = $this->report->getCountByStage($catalogCompleted->id);
        $asinCompleted   = Stage::where('stage_name', '(Central) ASIN Created')->first();
        $total_asin = $this->report->getCountByStage($asinCompleted->id);

        // Get By Status
        $statusOpen   = Status::where('status_name', 'open')->first();
        $total_status = $this->report->getCountByStatus($statusOpen->id);

        return View::make('admin/report/demand', compact('total_request', 'total_sku',
                    'total_catlog', 'total_asin', 'total_status' ));
    }


}
