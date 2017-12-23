<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class SettingsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('personal_info', 'static', [
                'tag' => 'div',
                'attr' => ['class' => 'badge'],
                'label' => false,
                'value' => 'Personal Information'
            ])
            ->add('name', 'text', [
                'rules' => 'required',
                'value' => user()->name
            ])
            ->add('password', 'password', [
                'rules' => 'sometimes|confirmed'
            ])
            ->add('password_confirmation', 'password')
            ->add('basecamp_info', 'static', [
                'tag' => 'div',
                'label' => false,
                'attr' => ['class' => 'badge'],
                'value' => 'Basecamp Information'
            ])
            ->add('basecamp_org', 'text', [
                'rules' => 'required',
                'label' => 'Basecamp Company Name',
                'value' => user()->basecamp_org
            ])
            ->add('basecamp_api_key', 'text', [
                'rules' => 'required',
                'label' => 'Basecamp API Key',
                'value' => user()->basecamp_api_key
            ])
            ->add('basecamp_api_user_id', 'text', [
                'rules' => 'required',
                'label' => 'Basecamp User ID',
                'value' => user()->basecamp_api_user_id
            ])
            ->add('<i class="glyphicon glyphicon-ok"> Save</i>', 'submit', [
                'attr' => ['class' => 'btn btn-success pull-right']
            ]);
    }
}
