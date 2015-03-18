<?php

class DashboardController extends BaseController {

    /**
     * User Model
     * @var User
     */
    protected $user;

    /**
     * Inject the models.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
    }

	/**
	 * Returns all the blog posts.
	 *
	 * @return View
	 */
	public function getIndex()
	{

    $priority = '1:Low;2:Medium;3:High';
    // Get all Status
    $statusArray = Status::all();
    $status = '';
    if($statusArray) {
      foreach($statusArray as $key => $value) {
        $status .= $value['id'].":".$value['status_name'].';';
      }
    }
    // Get all Group
    $groupArray = Group::all();
    $group = '';
    if($groupArray) {
      foreach($groupArray as $key => $value) {
        $group .= $value['id'].":".$value['group_name'].';';
      }
    }
    // Get all Stage
    $stageArray = Stage::all();
    $stage = '';
    if($stageArray) {
      foreach($stageArray as $key => $value) {
        $stage .= $value['id'].":".$value['stage_name'].';';
      }
    }
    // 'E:English;S:Spanish;G:German'
		// Show the page
    // View::make('site/dashboards/locallead');
		return View::make('site/dashboards/locallead', compact('posts', 'priority', 'group', 'stage', 'status'));
	}


	public function getLead()
	{
			var_dump(Input::all());exit;
      // if (Request::wantsJson()) {
          return GridEncoder::encodeRequestedData(new LocalLeadRepository(new Ticket()), Input::all());
      // }
	}


}
