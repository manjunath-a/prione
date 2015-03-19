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

    list($user, $redirect) = User::checkAuthAndRedirect('user');
    if($redirect){return $redirect;}

    $photoGrapherArray = $this->user->findAllByRoleAndCity(3, $user->city_id);
    // var_dump($photoGrapherArray);exit;

    $photographer = '-1:select;';
    if($photoGrapherArray) {
      foreach($photoGrapherArray as $value) {
        $photographer .= $value->id.":".$value->username.';';
      }
    }

    $serviceAssociateArray = $this->user->findAllByRoleAndCity(4, $user->city_id);

    $serviceassociates = '-1:select;';
    if($serviceAssociateArray) {
      foreach($serviceAssociateArray as $value) {
        $serviceassociates .= $value->id.":".$value->username.';';
      }
    }

    $priority = '-1:select;1:Low;2:Medium;3:High';
    // Get all Status
    $statusArray = Status::all();
    $status = '-1:select;';
    if($statusArray) {
      foreach($statusArray as $key => $value) {
        $status .= $value['id'].":".$value['status_name'].';';
      }
    }
    // Get all Group
    $groupArray = Group::all();
    $group = '-1:select;';
    if($groupArray) {
      foreach($groupArray as $key => $value) {
        $group .= $value['id'].":".$value['group_name'].';';
      }
    }
    // Get all Stage
    $stageArray = Stage::all();
    $stage = '-1:select;';
    if($stageArray) {
      foreach($stageArray as $key => $value) {
        $stage .= $value['id'].":".$value['stage_name'].';';
      }
    }
		// Show the page
		return View::make('site/dashboards/locallead', compact('user', 'photographer',
          'serviceassociates', 'priority', 'group', 'stage', 'status'));
	}

	public function locallead()
	{
      GridEncoder::encodeRequestedData(new LocalLeadRepository(new Ticket()), Input::all());
	}

}
