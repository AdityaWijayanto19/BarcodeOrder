<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class AdminMenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }
    public function create()
    {
        return view('admin.menus.create');
    }
    public function store(Request $r)
    {
        $data = $r->validate(['name' => 'required', 'price' => 'required|integer', 'description' => 'nullable', 'available' => 'nullable']);
        $data['available'] = $r->has('available');
        Menu::create($data);
        return redirect()->route('menus.index');
    }
    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }
    public function update(Request $r, Menu $menu)
    {
        $data = $r->validate(['name' => 'required', 'price' => 'required|integer', 'description' => 'nullable', 'available' => 'nullable']);
        $data['available'] = $r->has('available');
        $menu->update($data);
        return redirect()->route('menus.index');
    }
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index');
    }
    public function show(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }
}
