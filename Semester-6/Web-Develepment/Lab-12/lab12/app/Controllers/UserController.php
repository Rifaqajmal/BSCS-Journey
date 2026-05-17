<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        $data['users'] = $model->findAll();

        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        helper(['form']);

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'phone' => 'required|numeric|exact_length[11]'
        ];

        if (!$this->validate($rules)) {
            return view('users/create', [
                'validation' => $this->validator
            ]);
        }

        $model = new UserModel();

        $model->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ]);

        return redirect()->to(base_url('users'));
    }

    public function edit($id)
    {
        $model = new UserModel();

        $data['user'] = $model->find($id);

        return view('users/edit', $data);
    }

    public function update($id)
    {
        helper(['form']);

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'phone' => 'required|numeric|exact_length[11]'
        ];

        if (!$this->validate($rules)) {

            $model = new UserModel();

            $data['user'] = $model->find($id);

            $data['validation'] = $this->validator;

            return view('users/edit', $data);
        }

        $model = new UserModel();

        $model->update($id, [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ]);

        return redirect()->to(base_url('users'));
    }

    public function delete($id)
    {
        $model = new UserModel();

        $model->delete($id);

        return redirect()->to(base_url('users'));
    }
}