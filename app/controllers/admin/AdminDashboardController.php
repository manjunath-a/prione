<?php

class AdminDashboardController extends AdminController {

    /**
     * Util Service
     * @var util
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
        $this->validateTicket = App::make('ticketValidator');
    }

  	/**
  	 * Admin dashboard
  	 *
  	 */
  	public function getIndex()
  	{
          return View::make('admin/dashboard');
  	}

    /**
     * Returns all the tickets on admin.
     *
     * @return View
     */
    public function getAdmin()
    {

        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $localTeamLeadArray   = $user->findUserByRoleName('Local Team Lead');
        $localTeamLead        = $this->util->arrayToJQString($localTeamLeadArray, 'username', 'id');

        $editingManagerArray   = $user->findUserByRoleName('Editing Manager');
        $editingManager        = $this->util->arrayToJQString($editingManagerArray, 'username', 'id');

        $catalogueManagerArray   = $user->findUserByRoleName('Catalogue Manager');
        $catalogueManager        = $this->util->arrayToJQString($catalogueManagerArray, 'username', 'id');

        $photoGrapherArray = $this->user->findAllByRoleAndCity('Photographer', $user->city_id);
        $photographer = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $this->user->findAllByRoleAndCity('Services Associate', $user->city_id);
        $serviceassociates = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id');

        // Show the page
        return View::make('admin/tickets/admin', compact('user', 'localTeamLead','editingManager' , 'catalogueManager','photographer',
            'serviceassociates', 'priority', 'photoshootLocation', 'group', 'stage', 'status', 'pending'));
    }


}