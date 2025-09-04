<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\UserRoleModel;
use App\Controllers\BaseController;

class UserRoleController extends BaseController
{
    protected $userRoleModel;
    protected $menuModel;
    protected $db;

    public function __construct()
    {
        $this->userRoleModel = new UserRoleModel();
        $this->menuModel = new MenuModel();
        $this->db = \Config\Database::connect();
    }

    // Menampilkan semua role
    public function index()
    {
        $data['roles'] = $this->userRoleModel->getRole();
        $data['title'] = 'User Role';
        return view('transaksi/user_role/index', $data);
    }

    // Menyimpan data baru
    public function create()
    {
        $menu_items = $this->menuModel->getMenu();
        $data['menu'] = $this->_render_menu($menu_items);

        // dd($data['menu']);

        $data['title'] = 'Add New Role';
        return view('transaksi/user_role/create', $data);
    }
    public function store()
    {
        $input = $this->request->getPost();
        $data = [
            'role_name'       => $this->request->getPost('role_name'),
            'role_create_by'  => session()->get('id'),
            'role_create_at'  => date('Y-m-d H:i:s'),
        ];

        $insert = $this->userRoleModel->insert($data);
        $id = 'role_' . $this->userRoleModel->getInsertID();

        // insert column to menu
        $query = "ALTER TABLE `menu` ADD `" . $id . "` enum('Y','N') DEFAULT 'N'";
        $this->db->query($query);

        $menu_items = $this->menuModel->getMenu();

        foreach ($menu_items as $item) {
            $update = "UPDATE `menu` 
				SET 
					`" . $id . "` = '" . $input['menu_' . $item['menu_id']] . "'
				WHERE `menu_id`= '" . $item['menu_id'] . "'";
            $this->db->query($update);
        }

        return redirect()->to('admin/settings/user_role')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $menu_items = $this->menuModel->getMenu();
        $data['menu'] = $this->_render_menu($menu_items, 0, '', 'role_' . $id);
        $data['roles'] = $this->userRoleModel->getRole($id);

        // dd($data['menu']);

        $data['title'] = 'Edit Role';
        return view('transaksi/user_role/edit', $data);
    }

    // Mengedit data berdasarkan ID
    public function update($id)
    {
        $input = $this->request->getPost();
        $data = [
            'role_name'      => $this->request->getPost('role_name'),
            'role_edit_by'   => session()->get('id'),
            'role_edit_at'   => date('Y-m-d H:i:s'),
        ];

        $update = $this->userRoleModel->update($id, $data);
        $menu_items = $this->menuModel->getMenu();

        foreach ($menu_items as $item) {
            $update = "UPDATE `menu` 
				SET 
					`role_" . $id . "` = '" . $input['menu_' . $item['menu_id']] . "'
				WHERE `menu_id`= '" . $item['menu_id'] . "'";
            $this->db->query($update);
        }

        return redirect()->to('admin/settings/user_role')->with('success', 'Data berhasil diubah.');
    }

    // Menghapus data secara soft delete
    public function delete($id)
    {
        $data = [
            'role_remove_by' => session()->get('id'),
            'role_remove_at' => date('Y-m-d H:i:s'),
            'role_status'    => 0,
        ];

        if ($this->userRoleModel->update($id, $data)) {
            return redirect()->to('admin/settings/user_role')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }

    /**
     * Render menu berdasarkan parent_id dan prefix
     * untuk form edit role
     *
     * @param array $menu_items
     * @param int $parent_id
     * @param string $prefix
     * @param string $id
     * @return string
     */
    // private function _render_menu($menu_items, $parent_id = 0, $prefix = '', $id = 'admin')
    // {
    //     $html = '';
    //     foreach ($menu_items as $item) {
    //         $main_menu_checked     = ($item[$id] == 'Y') ? 'checked' : null;
    //         $bold = ($parent_id == 0) ? 'font-weight:bold; color:blue' : null;
    //         $html .= '<tr>';
    //         if ($item['menu_parent'] == $parent_id) {
    //             $html .= '<td style="' . $bold . '">' . $prefix . ' ' . $item['menu_name'] . '</td>';
    //             $html .= '<td>'.$item['menu_link'] . '</td>';
    //             $html .= '<td class="text-center">
    // 										<div class="custom-control custom-checkbox">
    // 											<input type="hidden" name="menu_' . $item['menu_id'] . '" checked value="N">
    // 											<input type="checkbox" class="custom-control-input" id="check_' . $item['menu_id'] . '" name="menu_' . $item['menu_id'] . '" value="Y" ' . $main_menu_checked . '>
    // 											<label class="custom-control-label" for="check_' . $item['menu_id'] . '"> Bisa Mengakses </label>
    // 										</div>
    // 									</td>';
    //             // cek apakah item memiliki sub menu
    //             $has_children = false;
    //             foreach ($menu_items as $child) {
    //                 if ($child['menu_parent'] == $item['menu_id']) {
    //                     $has_children = true;
    //                     break;
    //                 }
    //             }
    //             // render sub menu jika ada
    //             if ($has_children) {
    //                 $html .= '<td>' . $this->_render_menu($menu_items, $item['menu_id'], '-', $id) . '</td>';
    //             }
    //         }
    //         $html .= '</tr>';
    //     }
    //     return $html;
    // }

    private function _render_menu($menu_items, $parent_id = 0, $prefix = '', $id = 'admin')
    {
        $html = '';
        foreach ($menu_items as $item) {

            if($item['menu_link'] == '#'){
                $link = $item['menu_link'];
            }else{
                $link = base_url('admin/' . $item['menu_link']);
            }


            if ($item['menu_parent'] == $parent_id) {
                $main_menu_checked = ($item[$id] == 'Y') ? 'checked' : '';
                $bold = ($parent_id == 0) ? 'font-weight:bold; color:blue' : '';

                $html .= '<tr>';
                $html .= '<td style="' . $bold . '">' . $prefix . ' ' . $item['menu_name'] . '</td>';
                $html .= '<td>' . $link . '</td>';
                $html .= '<td class="text-center">
                        <div class="custom-control custom-checkbox">
                            <input type="hidden" name="menu_' . $item['menu_id'] . '" value="N">
                            <input type="checkbox" class="custom-control-input" id="check_' . $item['menu_id'] . '" name="menu_' . $item['menu_id'] . '" value="Y" ' . $main_menu_checked . '>
                            <label class="custom-control-label" for="check_' . $item['menu_id'] . '">Bisa Mengakses</label>
                        </div>
                    </td>';
                $html .= '</tr>';

                // Render sub-menu jika ada
                $html .= $this->_render_menu($menu_items, $item['menu_id'], $prefix . '--', $id);
            }
        }

        return $html;
    }
}
