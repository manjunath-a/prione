<?php

class DashboardController extends BaseController {

    /**
     * User Model
     * @var User
     */
    protected $user;

     /**
     * Util Service
     * @var User
     */
    protected $util;

    /**
     * Inject the models.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
        $this->util = App::make('util');
    }

	/**
	 * Returns all the blog posts.
	 *
	 * @return View
	 */
	public function getLocalLead()
	{

        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $photoGrapherArray = $this->user->findAllByRoleAndCity('Photographer', $user->city_id);
        $photographer = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $this->user->findAllByRoleAndCity('Services Associate', $user->city_id);
        $serviceassociates = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $priority = '-1:select;1:Low;2:Medium;3:High';

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        // Show the page
        return View::make('site/dashboards/locallead', compact('user', 'photographer',
            'serviceassociates', 'priority', 'group', 'stage', 'status'));
    }

//	public function locallead()
//	{
//      GridEncoder::encodeRequestedData(new LocalLeadRepository(new Ticket()), Input::all());
//	}

    public function getPhotographer()
    {


        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $photoGrapherArray = $this->user->findAllByRoleAndCity('Photographer', $user->city_id);
        $photographer = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $this->user->findAllByRoleAndCity('Services Associate', $user->city_id);
        $serviceassociates = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $rules  = arraY('only' =>array('(Local) Associates Assigned',
                     '(Local) Photoshoot Completed / Seller Images Provided'));
        $stageArray = Stage::all();
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id', $rules);
        // Show the page
        return View::make('site/dashboards/photographer', compact('user', 'photographer',
            'serviceassociates', 'priority', 'group', 'stage', 'status'));

    }

    public function getMIF()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $photoGrapherArray = $this->user->findAllByRoleAndCity('Photographer', $user->city_id);
        $photographer = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $this->user->findAllByRoleAndCity('Services Associate', $user->city_id);
        $serviceassociates = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $rules  = arraY('only' =>array('(Local) Associates Assigned',
                '(Local) Photoshoot Completed / Seller Images Provided',
                '(Local) MIF Completed'));
        $stageArray = Stage::all();
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id', $rules);

        // Show the page
        return View::make('site/dashboards/mif', compact('user', 'photographer',
            'serviceassociates', 'priority', 'group', 'stage', 'status'));

    }

    public function getEditingManager()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $photoGrapherArray = $this->user->findAllByRoleAndCity('Photographer', $user->city_id);
        $photographer = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $this->user->findAllByRoleAndCity('Services Associate', $user->city_id);
        $serviceassociates = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        // Show the page
        return View::make('site/dashboards/editingmanager', compact('user', 'photographer',
            'serviceassociates', 'priority', 'group', 'stage', 'status'));

    }

    public function postSeller()
    {
        $sellerRequest  = new SellerRequest();
        $sellerId       = $sellerRequest->requetIdByTickectId(Input::get('id'));
        $seller         = SellerRequest::find($sellerId)->toArray();

        return Response::json(array(
                    "rows" => [
                                array(
                                    "cell" =>
                                            array(
                                                $seller['seller_name'],
                                                $seller['email'],
                                                $seller['contact_number'],
                                                $seller['poc_name'],
                                                $seller['poc_number'],
                                                $seller['poc_email']
                                            )
                                    )
                                ]
                            )
                    );
    }

}
