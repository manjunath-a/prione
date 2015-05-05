<?php

class TicketController extends BaseController
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
     * construct the models.
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->validateTicket = App::make('ticketValidator');
        $this->util = App::make('util');
        $this->user = $user;
    }

    /**
     * Returns Ticket Index Page.
     *
     * @return View
     */
    public function getIndex()
    {
        // Show the page
        return View::make('ticket/index');
    }

    /**
     * Returns Ticket closed Page.
     *
     * @return View
     */
    public function getTickets($statusname)
    {
        list($user, $redirect) = $this->user->checkAuthAndRedirect('user');

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
        return View::make('ticket/ticket', compact('user', 'photographer',
            'serviceassociates', 'priority', 'photoshootLocation', 'group', 'stage', 'status', 'statusname',
             'pending'));
    }
}
