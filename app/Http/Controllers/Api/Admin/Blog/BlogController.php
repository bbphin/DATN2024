<?php

namespace App\Http\Controllers\Api\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Blog $blog)
    {
        try {
            $blogs = Blog::latest()->paginate(10);
            return ApiResponse(true, Response::HTTP_OK, 'Successfully ', BlogResource::collection($blogs));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('/blog_images');
                $data['image'] = basename($imagePath); // Lưu tên file ảnh vào database
            }

            $blog = Blog::create($data);

            return ApiResponse(true, Response::HTTP_CREATED, 'Blog created successfully', new BlogResource($blog));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        try {
            $imageUrl = Storage::url('blog_images/' . $blog->image);

            // Trả về đường dẫn tới ảnh trong JSON response
            return response()->json([
                'success' => true,
                'message' => 'Image URL retrieved successfully',
                'imageUrl' => $imageUrl,
            ]);
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                // Xử lý khi có file ảnh mới
                $imagePath = $request->file('image')->store('/blog_images');
                $data['image'] = basename($imagePath); // Lưu tên file ảnh vào database

                // Xóa file ảnh cũ nếu có
                if (Storage::exists('public/blog_images/' . $blog->image)) {
                    Storage::delete('public/blog_images/' . $blog->image);
                }
            }

            $blog->update($data);

            return ApiResponse(true, Response::HTTP_OK, 'Blog updated successfully', new BlogResource($blog));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        try {
            // Xóa file ảnh nếu có
            if (Storage::exists('public/blog_images/' . $blog->image)) {
                Storage::delete('public/blog_images/' . $blog->image);
            }

            $blog->delete();

            return ApiResponse(true, Response::HTTP_OK, 'Blog deleted successfully', null);
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), null);
        }
    }
}
