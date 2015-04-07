<?php

use Illuminate\Database\Eloquent\Model;

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

        $priority = '0:select;1:Low;2:Medium;3:High';
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

//        $rules  = arraY('only' =>array('Seller not reachable',
//            'Seller not giving appointment / not ready','Seller cancelled the appointment'));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id');

        // Show the page
        return View::make('site/dashboards/locallead', compact('user', 'photographer',
            'serviceassociates', 'priority', 'photoshootLocation', 'group', 'stage', 'status', 'pending'));
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
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $rules  = arraY('only' =>array('(Local) Associates Assigned',
                     '(Local) Photoshoot Completed / Seller Images Provided'));
        $stageArray = Stage::all();
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id', $rules);
        $pendingRules  = arraY('only' =>array('Seller not reachable',
                     'Seller cancelled the appointment'));

        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $pendingRules);

        // Show the page
        return View::make('site/dashboards/photographer', compact('user', 'photographer',
            'serviceassociates', 'priority', 'photoshootLocation', 'group', 'stage', 'status', 'pending'));

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
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $rules  = arraY('only' =>array('(Local) Associates Assigned',
                '(Local) Photoshoot Completed / Seller Images Provided',
                '(Local) MIF Completed'));
        $stageArray = Stage::all();
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id', $rules);

        $pendingRules  = arraY('only' =>array('Seller not reachable',
                     'Seller cancelled the appointment',
                     'Seller not providing data for building MIF'));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $pendingRules);

        // Show the page
        return View::make('site/dashboards/mif', compact('user', 'photographer',
            'serviceassociates', 'priority', 'photoshootLocation', 'group', 'stage', 'status', 'pending'));

    }

    public function getEditingManager()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $user              = new User;
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        // Show the page
        return View::make('site/dashboards/editingmanager', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead','priority', 'photoshootLocation', 'group',
            'stage', 'status'));

    }

    public function getEditingTeamLead()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $user              = new User;
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id');

        // Show the page
        return View::make('site/dashboards/editingteamlead', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead','editor','priority', 'photoshootLocation',
            'group', 'stage', 'status', 'pending'));

    }

    public function getEditor()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $user              = new User;
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        $rules  = arraY('only' =>array('Images QC Failed'));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $rules);

        // Show the page
        return View::make('site/dashboards/editor', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead','editor','priority', 'photoshootLocation',
            'group', 'stage', 'status', 'pending'));

    }

    public function getCatalogueManager()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $user              = new User;
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $catalogueTeamLeadArray= $user->findUserByRoleName('Catalogue Team Lead');
        $catalogueTeamLead     = $this->util->arrayToJQString($catalogueTeamLeadArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        // Show the page
        return View::make('site/dashboards/cataloguemanager', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead','editor','catalogueTeamLead','priority', 'photoshootLocation',
             'group', 'stage', 'status'));

    }

    public function getCatalogueTeamLead()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $user              = new User;
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $catalogueTeamLeadArray= $user->findUserByRoleName('Catalogue Team Lead');
        $catalogueTeamLead     = $this->util->arrayToJQString($catalogueTeamLeadArray, 'username', 'id');

        $cataloguerArray       = $user->findUserByRoleName('Cataloguer');
        $cataloguer            = $this->util->arrayToJQString($cataloguerArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        // Show the page
        return View::make('site/dashboards/catalogueteamlead', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead','editor','catalogueTeamLead','cataloguer','priority',
            'photoshootLocation', 'group', 'stage', 'status'));

    }

    public function getCataloguer()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $user              = new User;
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray,'username','id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $catalogueTeamLeadArray= $user->findUserByRoleName('Catalogue Team Lead');
        $catalogueTeamLead     = $this->util->arrayToJQString($catalogueTeamLeadArray, 'username', 'id');

        $cataloguerArray       = $user->findUserByRoleName('Cataloguer');
        $cataloguer            = $this->util->arrayToJQString($cataloguerArray, 'username', 'id');

        $priority = '0:select;1:Low;2:Medium;3:High';
        $photoshootLocation = '0:select;Studio:Studio;2:Seller Site';

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $rules  = arraY('only' =>array('(Central) Cataloging Completed',
                     '(Central) ASIN Created'));

        $stageArray = Stage::all();
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id', $rules);

        // Show the page
        return View::make('site/dashboards/cataloguer', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead','editor','catalogueTeamLead','cataloguer',
            'priority', 'photoshootLocation', 'group', 'stage', 'status'));

    }
    public function postSeller()
    {
        $sellerRequest  = new SellerRequest();
        $sellerId       = $sellerRequest->requetIdByTicketId(Input::get('id'));
        $seller         = SellerRequest::find($sellerId)->toArray();
        // var_dump($seller['image_available']);
        $seller['image_available'] = ($seller['image_available']==1)?'No':'Yes';
        // var_dump($seller['image_available']);exit;
        return Response::json(array(
                    "rows" => [
                                array(
                                    "cell" =>
                                            array(
                                                $seller['merchant_name'],
                                                $seller['poc_name'],
                                                $seller['poc_email'],
                                                $seller['poc_number'],
                                                $seller['image_available']
                                            )
                                    )
                                ]
                            )
                    );
    }

    public function postEditing()
    {
        $sellerRequest      = new SellerRequest;
        $ticketTransaction  = new TicketTransaction;
        $user               = new User;

        $sellerId       = $sellerRequest->requetIdByTicketId(Input::get('id'));
        $seller         = SellerRequest::find($sellerId)->toArray();
        $ticket         = $ticketTransaction->transactionByTicketId(Input::get('id'));

        $data['city']   = City::find($seller['merchant_city_id'])->toArray();
        $photographer   = User::find($ticket['photographer_id'])->toArray();
        $mif            = User::find($ticket['mif_id'])->toArray();
        $editor = NULL;
        if($ticket['editor_id']) {
            $editorArray     = User::find($ticket['editor_id'])->toArray();
            $editor = $editorArray['username'];
        }
        $data['loalLead']= $user->findAllByRoleAndCity('Local Team Lead', $seller['merchant_city_id']);

        return Response::json(array(
                "rows" => [
                    array(
                        "cell" =>
                            array(
                                $data['city']['city_name'],
                                $data['loalLead'][0]->username,
                                $photographer['username'],
                                $mif['username'],
                                $editor
                            )
                    )
                ]
            )
        );
    }
}
