<?php

use Illuminate\Database\Eloquent\Model;

class DashboardController extends BaseController
{
    /**
     * User Model.
     *
     * @var User
     */
    protected $user;

    /**
     * Util Service.
     *
     * @var util
     */
    protected $util;

    /**
     * TicketValidator.
     *
     * @var validateTicket
     */
    protected $validateTicket;

    /**
     * Inject the models.
     *
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
     * Returns all the Locl Lead tickects.
     *
     * @return View
     */
    public function getLocalLead()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if ($redirect) {
            return $redirect;
        }

        $photoGrapherArray = $this->user->findAllByRoleAndCity('Photographer', $user->city_id);
        $photographer = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $this->user->findAllByRoleAndCity('Services Associate', $user->city_id);
        $serviceassociates = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

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
        if ($redirect) {
            return $redirect;
        }

        $photoGrapherArray = $this->user->findAllByRoleAndCity('Photographer', $user->city_id);
        $photographer = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $this->user->findAllByRoleAndCity('Services Associate', $user->city_id);
        $serviceassociates = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

//        $rules  = arraY('only' =>array('(Local) Associates Assigned',
//                     '(Local) Photoshoot Completed / Seller Images Provided'));
        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        $pendingRules  = array('only' => array('Seller not reachable',
                     'Seller cancelled the appointment', ));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $pendingRules);

        // Show the page
        return View::make('site/dashboards/photographer', compact('user', 'photographer',
            'serviceassociates', 'priority', 'photoshootLocation', 'group', 'stage', 'status', 'pending'));
    }

    public function getMIF()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if ($redirect) {
            return $redirect;
        }

        $photoGrapherArray = $this->user->findAllByRoleAndCity('Photographer', $user->city_id);
        $photographer = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $this->user->findAllByRoleAndCity('Services Associate', $user->city_id);
        $serviceassociates = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

        $statusArray = Status::all();
        $status = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

//        $rules  = arraY('only' =>array('(Local) Associates Assigned',
//                '(Local) Photoshoot Completed / Seller Images Provided',
//                '(Local) MIF Completed'));
        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        $pendingRules  = array('only' => array('Seller not reachable',
                     'Seller cancelled the appointment',
                     'Seller not providing data for building MIF', ));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $pendingRules);

        // Show the page
        return View::make('site/dashboards/mif', compact('user', 'photographer',
            'serviceassociates', 'priority', 'photoshootLocation', 'group', 'stage', 'status', 'pending'));
    }

    public function getEditingManager()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if ($redirect) {
            return $redirect;
        }

        $user              = new User();
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        // Show the page
        return View::make('site/dashboards/editingmanager', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead', 'priority', 'photoshootLocation', 'group',
            'stage', 'status'));
    }

    public function getEditingTeamLead()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if ($redirect) {
            return $redirect;
        }

        $user              = new User();
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        $pendingRules   = array('only' => array('Editing Images QC Failed', 'Raw Images QC Failed'));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $pendingRules);

        // Show the page
        return View::make('site/dashboards/editingteamlead', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead', 'editor', 'priority', 'photoshootLocation',
            'group', 'stage', 'status', 'pending'));
    }

    public function getEditor()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if ($redirect) {
            return $redirect;
        }

        $user              = new User();
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        $rules  = array('only' => array('Editing Images QC Failed', 'Raw Images QC Failed'));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $rules);

        // Show the page
        return View::make('site/dashboards/editor', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead', 'editor', 'priority', 'photoshootLocation',
            'group', 'stage', 'status', 'pending'));
    }

    public function getCatalogManager()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if ($redirect) {
            return $redirect;
        }

        $user              = new User();
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $catalogueTeamLeadArray = $user->findUserByRoleName('Catalog Team Lead');
        $catalogueTeamLead     = $this->util->arrayToJQString($catalogueTeamLeadArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        // Show the page
        return View::make('site/dashboards/catalogmanager', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead', 'editor', 'catalogueTeamLead', 'priority', 'photoshootLocation',
             'group', 'stage', 'status'));
    }

    public function getCatalogTeamLead()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if ($redirect) {
            return $redirect;
        }

        $user              = new User();
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $catalogueTeamLeadArray = $user->findUserByRoleName('Catalog Team Lead');
        $catalogueTeamLead     = $this->util->arrayToJQString($catalogueTeamLeadArray, 'username', 'id');

        $cataloguerArray       = $user->findUserByRoleName('Cataloger');
        $cataloguer            = $this->util->arrayToJQString($cataloguerArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id');

        $pendingRules   = array('only' => array('MIF QC failed', 'Flat file QC failed'));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $pendingRules);
        // Show the page
        return View::make('site/dashboards/catalogteamlead', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead', 'editor', 'catalogueTeamLead', 'cataloguer', 'priority',
            'photoshootLocation', 'group', 'stage', 'status', 'pending'));
    }

    public function getCataloger()
    {
        list($user, $redirect) = User::checkAuthAndRedirect('user');
        if ($redirect) {
            return $redirect;
        }

        $user              = new User();
        $photoGrapherArray = $user->findUserByRoleName('Photographer');
        $photographer      = $this->util->arrayToJQString($photoGrapherArray, 'username', 'id');

        $serviceAssociateArray = $user->findUserByRoleName('Services Associate');
        $serviceassociates     = $this->util->arrayToJQString($serviceAssociateArray, 'username', 'id');

        $editingTeamLeadArray = $user->findUserByRoleName('Editing Team Lead');
        $editingteamlead      = $this->util->arrayToJQString($editingTeamLeadArray, 'username', 'id');

        $editorArray          = $user->findUserByRoleName('Editor');
        $editor               = $this->util->arrayToJQString($editorArray, 'username', 'id');

        $catalogueTeamLeadArray = $user->findUserByRoleName('Catalog Team Lead');
        $catalogueTeamLead     = $this->util->arrayToJQString($catalogueTeamLeadArray, 'username', 'id');

        $cataloguerArray       = $user->findUserByRoleName('Cataloger');
        $cataloguer            = $this->util->arrayToJQString($cataloguerArray, 'username', 'id');

        $priorityArray = Priority::all();
        $priority = $this->util->arrayToJQString($priorityArray, 'priority_name', 'id');

        $photoshootLocation = Config::get('ticket.photoshoot_location');

        $statusArray = Status::all();
        $status      = $this->util->arrayToJQString($statusArray, 'status_name', 'id');

        $groupArray = Group::all();
        $group      = $this->util->arrayToJQString($groupArray, 'group_name', 'id');

        $rules  = array('only' => array('(Central) Editing Completed', '(Central) Cataloging Completed', '(Central) QC Completed',
                     '(Central) ASIN Created', ));

        $stageArray = Stage::all();
        $stageArray = $stageArray->sortBy('sort');
        $stage      = $this->util->arrayToJQString($stageArray, 'stage_name', 'id', $rules);

        $pendingRules   = array('only' => array('MIF QC failed', 'Flat file QC failed'));
        $pendingArray   = PendingReason::all();
        $pending        = $this->util->arrayToJQString($pendingArray, 'pending_reason', 'id', $pendingRules);
        // Show the page
        return View::make('site/dashboards/cataloger', compact('user', 'photographer',
            'serviceassociates', 'editingteamlead', 'editor', 'catalogueTeamLead', 'cataloguer',
            'priority', 'photoshootLocation', 'group', 'stage', 'status', 'pending'));
    }

    public function postSeller()
    {
        $sellerRequest  = new SellerRequest();
        $ticketId = Input::get('id');
        $sellerId       = $sellerRequest->requetIdByTicketId($ticketId);
        $seller         = SellerRequest::find($sellerId)->toArray();
        $category   = Category::find($seller['category_id'])->toArray();

        $ticketTransaction = TicketTransaction::getAssignedUsersByTicketId($ticketId);
        $ticketUsers = $ticketTransaction[0];
        $seller['image_available'] = ($seller['image_available'] == 1) ? 'No' : 'Yes';
        return Response::json(array(
                    'rows' => [
                                array(
                                    'cell' => array(
                                                $category['category_name'],
                                                $seller['poc_name'],
                                                $seller['poc_email'],
                                                $seller['poc_number'],
                                                $seller['email'],
                                                $seller['contact_number'],
                                                $seller['image_available'],
                                                $ticketUsers->LocalTeamLead,
                                                $ticketUsers->Photographer,
                                                $ticketUsers->ServiceAssociate,
                                                $ticketUsers->EditingManager,
                                                $ticketUsers->EditingTeamLead,
                                                $ticketUsers->Editor,
                                                $ticketUsers->CatalogingManager,
                                                $ticketUsers->CatalogingTeamLead,
                                                $ticketUsers->Cataloger,
                                                $ticketUsers->RejectedBy
                                            ),
                                    ),
                                ],
                            )
                    );
    }

    public function postSellerInfo()
    {
        $sellerRequest  = new SellerRequest();
        $ticketId = Input::get('id');
        $sellerId       = $sellerRequest->requetIdByTicketId($ticketId);
        $seller         = SellerRequest::find($sellerId)->toArray();
        $category   = Category::find($seller['category_id'])->toArray();

        $ticketTransaction = TicketTransaction::getAssignedUsersByTicketId($ticketId);
        $ticketUsers = $ticketTransaction[0];
        $seller['image_available'] = ($seller['image_available'] == 1) ? 'No' : 'Yes';
        return Response::json(array(
                    'rows' => [
                                array(
                                    'cell' => array(
                                                $seller['created_at'],
                                                $category['category_name'],
                                                $seller['poc_name'],
                                                $seller['poc_email'],
                                                $seller['poc_number'],
                                                $seller['requester_name'],
                                                $seller['email'],
                                                $seller['contact_number'],
                                                $seller['image_available'],
                                                $ticketUsers->RejectedBy
                                            ),
                                    ),
                                ],
                            )
                    );
    }

    public function postEditing()
    {
        $sellerRequest      = new SellerRequest();
        $ticketTransaction  = new TicketTransaction();
        $user               = new User();
        $ticketId = Input::get('id');
        $sellerId       = $sellerRequest->requetIdByTicketId($ticketId);
        $seller         = SellerRequest::find($sellerId)->toArray();
        $category   = Category::find($seller['category_id'])->toArray();
        $ticket         = $ticketTransaction->transactionByTicketId($ticketId);

        $ticketTransaction = TicketTransaction::getAssignedUsersByTicketId($ticketId);
        $ticketUsers = $ticketTransaction[0];

        $data['city']   = City::find($seller['merchant_city_id'])->toArray();

        return Response::json(array(
                'rows' => [
                    array(
                        'cell' => array(
                                $seller['created_at'],
                                $seller['email'],
                                $seller['contact_number'],
                                $category['category_name'],
                                $data['city']['city_name'],
                                $ticketUsers->LocalTeamLead,
                                $ticketUsers->Photographer,
                                $ticketUsers->ServiceAssociate,
                                $ticketUsers->EditingManager,
                                $ticketUsers->EditingTeamLead,
                                $ticketUsers->Editor,
                                $ticketUsers->CatalogingManager,
                                $ticketUsers->CatalogingTeamLead,
                                $ticketUsers->Cataloger,
                                $ticketUsers->RejectedBy

                            ),
                    ),
                ],
            )
        );
    }
}
