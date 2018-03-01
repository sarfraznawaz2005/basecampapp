<?php

namespace App\Forms;

use anlutro\LaravelSettings\SettingStore;
use Kris\LaravelFormBuilder\Form;

class SettingsForm extends Form
{
    protected $setting = null;

    public function __construct(SettingStore $settingStore)
    {
        $this->setting = $settingStore;
    }

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
            ->add('password_confirmation', 'password', [
                'label' => 'Password Confirmation',
            ])
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
            ->add('other_hr', 'static', [
                'tag' => 'hr',
                'label' => false,
                'attr' => ['class' => ''],
                'value' => ''
            ])
            ->add('other_info', 'static', [
                'tag' => 'div',
                'label' => false,
                'attr' => ['class' => 'badge'],
                'value' => 'Other Settings'
            ])
            ->add('daily_hours', 'text', [
                'label' => 'Daily Required Hours',
                'value' => $this->setting->get('daily_hours') ?: 8
            ])
            /*
            ->add('holidays', 'text', [
                'label' => 'Public Holidays This Month',
                'value' => $this->setting->get('holidays') ?: 0
            ])
            */
            ->add('<i class="glyphicon glyphicon-ok"> Save</i>', 'submit', [
                'attr' => ['class' => 'btn btn-success pull-right']
            ]);
    }
}
