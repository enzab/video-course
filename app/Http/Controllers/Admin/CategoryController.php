<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
            tampung seluruh data category kedalam variable $category,
            selanjutnya kita pecah data category yang kita tampilkan hanya 10 per halaman dengan urutan terbaru.
        */
        $categories = Category::latest()->paginate(10);

        // passing variable $category kedalam view
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        // tampung request file image kedalam variable $image
        $image = $request->file('image');

        //request yang telah kita tampung kedalam variable, kita masukkan kedalam folder public/categories
        $image->storeAs('public/categories', $image->hashName());

        // masukkan data baru category kedalam database.
        Category::create([
            'name' => $request->name,
            'image' => $image->hashName()
        ]);

        // kembali ke halaman admin/category/index dengan membawa toast
        return redirect(route('admin.category.index'))->with('toast_success', 'Category Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        // passing variable $category kedalam view
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        // update data category berdasarkan id
        $category->update([
            'name' => $request->name
        ]);

        // cek apakah user mengirimkan request file image
        if($request->file('image')) {
            // hapus image category yang sebelumnya
            Storage::disk('local')->delete('public/categories/'.basename($category->image));
            // tampung request file image kedalam variable $image
            $image = $request->file('image');
            // request yang telah kita tampung kedalam variable kita masukkan kedalam folder public/categories
            $image->storeAs('public/categories', $image->hashName());
            //update data category image berdasarkan id
            $category->update([
                'image' => $image->hashName(),
            ]);
        }

        // kembali kehalaman admin/category/index dengan membawa toaster
        return redirect(route('admin.category.index'))->with('toast_success', 'Category Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // hapus image category berdasarkan id
        Storage::disk('local')->delete('public/categories/'.basename($category->image));

        
        // hapus data category berdasarkan id
        $category->delete();

        // kembali kehalaman sebelumnya dengan membawa toaster
        return back()->with('toast_success', 'Category Deleted');

    }
}
