<?php

namespace App\Http\Controllers;

use App\Forms\SettingsForm;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

    public function update(FormBuilder $formBuilder, User $user)
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

        flash('Updated Successfully!', 'success');
        return redirect()->back();
    }
}
