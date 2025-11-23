<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VendorRequest;
use App\Services\Admin\VendorService;

class VendorController extends Controller
{

    protected $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->vendorService = $vendorService;
    }

    public function index()
    {
        $vendors = $this->vendorService->getAll();

        return view('admin.vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendor.create');
    }

    public function store(VendorRequest $request)
    {
        $this->vendorService->create($request->validated());
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    public function edit($id)
    {
        $vendor = $this->vendorService->find($id);

        return view('admin.vendor.edit', compact('vendor'));
    }

    public function update(VendorRequest $request, $id)
    {
        $this->vendorService->update($id, $request->validated());

        return redirect()
            ->route('admin.vendor.index')
            ->with('success', 'Vendor has been updated.');
    }

    public function destroy($id)
    {
        $this->vendorService->delete($id);
        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Vendor has been deleted.');
    }
}
