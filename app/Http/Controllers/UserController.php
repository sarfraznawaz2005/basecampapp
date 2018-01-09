<?php

namespace App\Http\Controllers;

use anlutro\LaravelSettings\SettingStore;
use App\DataTables\UsersDataTable;
use App\Facades\Data;
use App\Forms\SettingsForm;
use App\Models\User;
use Kris\LaravelFormBuilder\FormBuilder;

class UserController extends Controller
{
    public function index(FormBuilder $formBuilder)
    {
        title('Settings');

        $form = $formBuilder->create(SettingsForm::class, [
            'method' => 'POST',
            'url' => route('settings')
        ]);

        return view('pages.settings.settings', compact('form'));
    }

    public function update(FormBuilder $formBuilder, User $user, SettingStore $settingStore)
    {
        $form = $formBuilder->create(SettingsForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $user = $user->find(user()->id);

        $user->name = request()->name;
        $user->basecamp_org = request()->basecamp_org;
        $user->basecamp_api_key = request()->basecamp_api_key;
        $user->basecamp_api_user_id = request()->basecamp_api_user_id;

        // update password if specified
        if (trim(request()->password)) {
            $this->validate(request(), [
                'password' => 'sometimes|min:6',
            ]);

            $user->password = bcrypt(request()->password);
        }

        $user->save();

        Data::checkConnection($user->basecamp_api_user_id);

        // save other settings
        $settingStore->set('holidays', request()->holidays);
        $settingStore->set('daily_hours', request()->daily_hours);
        $settingStore->save();

        flash('Updated Successfully!', 'success');
        return redirect()->back();
    }

    /**
     * Lists users.
     *
     * @param UsersDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function listUsers(UsersDataTable $dataTable)
    {
        title('Users');

        return $dataTable->render('shared.table');
    }

    /**
     * Login as user.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(User $user)
    {
        session(['isloginas' => true]);

        auth()->loginUsingId($user->id);

        flash('Logged in as ' . $user->name, 'success');

        return redirect()->to(route('home'));
    }

    /**
     * Revert Login as user.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function RevertLoginAs(User $user)
    {
        session(['isloginas' => false]);

        auth()->loginUsingId($user->id);

        flash('Welcome back ' . $user->name, 'success');

        return redirect()->to(route('users'));
    }
}
