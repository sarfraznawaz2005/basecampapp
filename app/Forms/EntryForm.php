<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class EntryForm extends Form
{
    public function buildForm()
    {

        $projects = user()->projectsAll->pluck('project_name', 'project_id')->toArray();
        asort($projects);

        $this
            ->add('date', 'date', [
                'rules' => 'required',
                'label' => false,
                'attr' => ['style' => 'width:100%'],
                'value' => date('Y-m-d'),
            ])
            ->add('project', 'select', [
                'rules' => 'required',
                'choices' => $projects,
                'attr' => ['style' => 'width:100%'],
                'empty_value' => 'Select'
            ])
            ->add('todolist', 'select', [
                'rules' => 'required',
                'attr' => ['style' => 'width:100%', 'disabled' => true],
                'empty_value' => 'Select'
            ])
            ->add('todo', 'select', [
                'rules' => 'required',
                'attr' => ['style' => 'width:100%', 'disabled' => true],
                'empty_value' => 'Select'
            ])
            ->add('description', 'text', [
                'rules' => 'required',
                'label' => false,
                'attr' => ['placeholder' => 'Description'],
            ])
            ->add('time_start', 'time', [
                'rules' => 'required',
                'label' => 'Time Start',
                'attr' => ['style' => 'width:100%'],
            ])
            ->add('time_end', 'time', [
                'rules' => 'required',
                'label' => 'Time End',
                'attr' => ['style' => 'width:100%'],
            ]);
    }
}
