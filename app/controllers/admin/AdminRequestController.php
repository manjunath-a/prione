<?php

class AdminRequestController extends AdminController
{
    /**
     * Seller Model.
     *
     * @var User
     */
    protected $sellerRequest;

    /**
     * Inject the models.
     *
     * @param User       $user
     * @param Role       $role
     * @param Permission $permission
     */
    public function __construct(SellerRequest $sellerRequest)
    {
        parent::__construct();
        $this->sellerRequest = $sellerRequest;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        // Title
        $title = Lang::get('admin/sellerrequest/title.seller_request');

        // Grab all the users
        $users = $this->user;

        // Show the page
        return View::make('/admin/sellerrequest/index', compact('users', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        // All roles
        $roles = $this->role->all();

        // Get all the available permissions
        $permissions = $this->permission->all();

        // Selected groups
        $selectedRoles = Input::old('roles', array());

        // Get all the available city
        $cities = City::all();
        foreach ($cities as $key => $cityArray) {
            $city[$cityArray['id']] = $cityArray['city_name'];
        }

        // Selected permissions
        $selectedPermissions = Input::old('permissions', array());

        // Title
        $title = Lang::get('admin/sellerrequest/title.create_a_new_user');

        // Mode
        $mode = 'create';

        // Show the page
        return View::make('admin/sellerrequest/create_edit', compact('roles',
            'permissions', 'city', 'selectedRoles', 'selectedPermissions',
            'title', 'mode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate()
    {
        $this->user->username = Input::get('username');
        $this->user->email = Input::get('email');
        $this->user->password = Input::get('password');
        $this->user->city_id = Input::get('city_id');

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $this->user->password_confirmation = Input::get('password_confirmation');

        // Generate a random confirmation code
        $this->user->confirmation_code = md5(uniqid(mt_rand(), true));

        if (Input::get('confirm')) {
            $this->user->confirmed = Input::get('confirm');
        }

        // Permissions are currently tied to roles. Can't do this yet.
        //$user->permissions = $user->roles()->preparePermissionsForSave(Input::get( 'permissions' ));

        // Save if valid. Password field will be hashed before save
        $this->user->save();

        if ($this->user->id) {
            // Save roles. Handles updating.
            $this->user->saveRoles(Input::get('roles'));

            if (Config::get('confide::signup_email')) {
                $user = $this->user;
                Mail::queueOn(
                    Config::get('confide::email_queue'),
                    Config::get('confide::email_account_confirmation'),
                    compact('user'),
                    function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->username)
                            ->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
                    }
                );
            }

            // Redirect to the new user page
            return Redirect::to('admin/sellerrequest/'.$this->user->id.'/edit')
                ->with('success', Lang::get('admin/sellerrequest/messages.create.success'));
        } else {

            // Get validation errors (see Ardent package)
            $error = $this->user->errors()->all();

            return Redirect::to('admin/sellerrequest/create')
                ->withInput(Input::except('password'))
                ->with('error', $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $user
     *
     * @return Response
     */
    public function getShow($user)
    {
        // redirect to the frontend
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     *
     * @return Response
     */
    public function getEdit($user)
    {
        if ($user->id) {
            $roles = $this->role->all();
            $permissions = $this->permission->all();
            // Get all the available city
            $cities = City::all();
            foreach ($cities as $key => $cityArray) {
                $city[$cityArray['id']] = $cityArray['city_name'];
            }

            // Title
            $title = Lang::get('admin/sellerrequest/title.request_update');
            // mode
            $mode = 'edit';

            return View::make('admin/sellerrequest/create_edit', compact('user', 'roles',
                'permissions', 'city', 'title', 'mode'));
        } else {
            return Redirect::to('admin/sellerrequest')->with('error', Lang::get('admin/sellerrequest/messages.does_not_exist'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     *
     * @return Response
     */
    public function postEdit($user)
    {
        $oldUser = clone $user;
        $user->username = Input::get('username');
        $user->email = Input::get('email');
        $user->confirmed = Input::get('confirm');
        $user->city_id = Input::get('city_id');

        $password = Input::get('password');
        $passwordConfirmation = Input::get('password_confirmation');

        if (!empty($password)) {
            if ($password === $passwordConfirmation) {
                $user->password = $password;
                // The password confirmation will be removed from model
                // before saving. This field will be used in Ardent's
                // auto validation.
                $user->password_confirmation = $passwordConfirmation;
            } else {
                // Redirect to the new user page
                return Redirect::to('admin/sellerrequest/'.$user->id.'/edit')->with('error', Lang::get('admin/users/messages.password_does_not_match'));
            }
        }

        if ($user->confirmed == null) {
            $user->confirmed = $oldUser->confirmed;
        }

        if ($user->save()) {
            // Save roles. Handles updating.
            $user->saveRoles(Input::get('roles'));
        } else {
            return Redirect::to('admin/sellerrequest/'.$user->id.'/edit')
                ->with('error', Lang::get('admin/sellerrequest/messages.edit.error'));
        }

        // Get validation errors (see Ardent package)
        $error = $user->errors()->all();

        if (empty($error)) {
            // Redirect to the new user page
            return Redirect::to('admin/sellerrequest/'.$user->id.'/edit')->with('success', Lang::get('admin/users/messages.edit.success'));
        } else {
            return Redirect::to('admin/sellerrequest/'.$user->id.'/edit')->with('error', Lang::get('admin/users/messages.edit.error'));
        }
    }

    /**
     * Remove user page.
     *
     * @param $user
     *
     * @return Response
     */
    public function getDelete($user)
    {
        // Title
        $title = Lang::get('admin/sellerrequest/title.request_delete');

        // Show the page
        return View::make('admin/sellerrequest/delete', compact('user', 'title'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param $user
     *
     * @return Response
     */
    public function postDelete($user)
    {
        // Check if we are not trying to delete ourselves
        if ($user->id === Confide::user()->id) {
            // Redirect to the user management page
            return Redirect::to('admin/sellerrequest')->with('error', Lang::get('admin/sellerrequest/messages.delete.impossible'));
        }

        AssignedRoles::where('user_id', $user->id)->delete();

        $id = $user->id;
        $user->delete();

        // Was the comment post deleted?
        $user = User::find($id);
        if (empty($user)) {
            // TODO needs to delete all of that user's content
            return Redirect::to('admin/sellerrequest')->with('success', Lang::get('admin/sellerrequest/messages.delete.success'));
        } else {
            // There was a problem deleting the user
            return Redirect::to('admin/sellerrequest')->with('error', Lang::get('admin/sellerrequest/messages.delete.error'));
        }
    }

    /**
     * Show a list of all the users formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $users = SellerRequest::leftjoin('city', 'seller_request.merchant_city_id', '=', 'city.id')
                            ->select(array('seller_request.id', 'seller_request.seller_name',
                        'seller_request.email', 'city.city_name as cityname',
                        'seller_request.contact_number', 'seller_request.created_at', ));

        return Datatables::of($users)

        ->edit_column('confirmed', '@if($confirmed)
                            Yes
                        @else
                            No
                        @endif')

        ->add_column('actions', '<a href="{{{ URL::to(\'admin/users/\' . $id . \'/edit\' ) }}}" class="iframe btn btn-xs btn-default">{{{ Lang::get(\'button.edit\') }}}</a>
                                @if($username == \'admin\')
                                @else
                                    <a href="{{{ URL::to(\'admin/users/\' . $id . \'/delete\' ) }}}" class="iframe btn btn-xs btn-danger">{{{ Lang::get(\'button.delete\') }}}</a>
                                @endif
            ')

        ->remove_column('id')

        ->make();
    }
}
