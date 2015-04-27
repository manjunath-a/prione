<?php

class AdminReportController extends AdminController
{
    /**
     * Util Service.
     *
     * @var util
     */
    protected $util;

    /**
     * Report Service.
     *
     * @var report
     */
    protected $report;

    /**
     * construct the models.
     */
    public function __construct()
    {
        parent::__construct();
        $this->util = App::make('util');
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
        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage = $this->util->selectOptions($stageArray, 'stage_name', 'id');

        $statusArray = Status::all();
        $status = $this->util->selectOptions($statusArray, 'status_name', 'id');

        $roleArray = Role::all();
        $role = $this->util->selectOptions($roleArray, 'name', 'id');

        $total_request_count = $this->report->getTotalRequest();
        $total_sku_count = $this->report->getTotalSKU();

        // Get By Stage
        $catalogCompleted   = Stage::where('stage_name', '(Central) Cataloging Completed')->first();
        $compelted = $catalogCompleted->id;
        $total_stage_count = $this->report->getCountByStage($catalogCompleted->id);

        // Get By Status
        $statusOpen   = Status::where('status_name', 'open')->first();
        $total_status_count = $this->report->getCountByStatus($statusOpen->id);
        $total_role_count = 0;

        return View::make('admin/report/demand', compact('stage', 'status', 'role', 'compelted',
         'total_request_count', 'total_sku_count',  'total_stage_count',  'total_status_count',
         'total_role_count'));
    }

    public function getStatus($statusId)
    {
        $this->layout = null;
        $count = $this->report->getCountByStatus($statusId);
        $total_status = array('status' => true, 'count' => $count);
        echo json_encode($total_status);
        exit;
    }

    public function getStage($stageId)
    {
        $this->layout = null;
        $count = $this->report->getCountByStage($stageId);
        $total_stage = array('status' => true, 'count' => $count);
        echo json_encode($total_stage);
        exit;
    }

    public function getRole($roleId)
    {
        $this->layout = null;
        $count = $this->report->getCountByStatus($roleId);
        $total_role = array('status' => true, 'count' => $count);
        echo json_encode($total_role);
        exit;
    }
}
