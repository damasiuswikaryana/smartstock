<?php

namespace App\Helpers;

use App\Models\Menu;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;


class MenuHeader
{
    public static function getMenu()
    {
        $user                   = User::with('roles')->where('id', Auth::id())->first();
        $role_name              = $user->roles[0]->name;
        $role                   = Role::findByName($role_name);
        $permissions            = $role->permissions()->get()->toArray();
        $collection_permission  = [];

        foreach ($permissions as $item) {
            $collection_permission[] = $item['name'];
        }
        $menus              = new Menu();
        $menus              = Menu::whereIn('permission', $collection_permission)->orderBy('order', 'ASC')->get()->toArray();
        $collection_menu    = [];

        foreach ($menus as $row) {
            $row['type']        = MenuHeader::setType($row);
            $collection_menu[]  = $row;
        }
        $tree_menu              = MenuHeader::buildNestedArray($collection_menu, 0);
        return $tree_menu;
    }

    public static function getMenuWithoutRole()
    {

        $menus              = new Menu();
        $menus              = Menu::orderBy('order', 'ASC')->get()->toArray();
        $collection_menu    = [];

        foreach ($menus as $row) {
            $row['type']        = MenuHeader::setType($row);
            $collection_menu[]  = $row;
        }
        $tree_menu          = MenuHeader::buildNestedArray($collection_menu, 0);
        return $tree_menu;
    }

    public static function buildNestedArray(array $nodes = [], $parentId = 0)
    {
        $branch = [];
        foreach ($nodes as $node) {
            if ($node['parent_id'] == $parentId) {
                $children = MenuHeader::buildNestedArray($nodes, $node['id']);
                if ($children) {
                    $node['children'] = $children;
                }
                $branch[] = $node;
            }
        }
        return $branch;
    }

    public static function setType($row)
    {
        if ($row['parent_id'] == 0) return "root";
        $get_child = Menu::where([
            'parent_id' => $row['id']
        ])->first();
        return !empty($get_child) ? "child" : "subchild";
    }
}
