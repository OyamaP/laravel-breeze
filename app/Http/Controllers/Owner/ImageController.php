<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');
        // ログインしたオーナー権限ではない画像情報の参照対策
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('image');
            if(!is_null($id)) {
                $imagesOwnerId = Image::findOrFail($id)->owner->id;
                // owners.id !== image.owner_id
                if(Auth::id() !== (int)$imagesOwnerId) abort(404);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::where('owner_id', Auth::id())->orderBy('updated_at', 'desc')->paginate(20);
        return view('owner.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        $request->validate([
            'title' => 'string|max:50',
        ]);
        $imageFiles = $request->file('files');
        if(!is_null($imageFiles)) {
            foreach($imageFiles as $imageFile) {
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => ImageService::upload($imageFile, 'products'),
                    'title' => $request->title,
                ]);
            }
        }

        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => 'Update Images Data',
                'status' => 'info'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('owner.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'string|max:50',
        ]);

        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->save();

        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => 'Update Image Data',
                'status' => 'info'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        if($image->filename === 'sample.jpg') {
            return redirect()
                ->route('owner.images.index')
                ->with([
                    'message' => 'Notice: This image is common sample image',
                    'status' => 'alert',
                ]);
        }

        try
        {
            DB::transaction(function () use($id,$image) {
                // 画像をProductのいずれかで利用している場合外部キー制約でエラーとなる
                // image1~4でidを検索して合致したカラムをnullにすることで回避
                $imageInProducts = Product::where('image1', $image->id)
                    ->orWhere('image2', $image->id)
                    ->orWhere('image3', $image->id)
                    ->orWhere('image4', $image->id)
                    ->get();
                if($imageInProducts) {
                    $imageInProducts->each(function($product) use($image){
                        if($product->image1 === $image->id) $product->image1 = null;
                        if($product->image2 === $image->id) $product->image2 = null;
                        if($product->image3 === $image->id) $product->image3 = null;
                        if($product->image4 === $image->id) $product->image4 = null;
                        $product->save();
                    });
                }
                Image::findOrFail($id)->delete();
            }, 2);
        }
        catch(Throwable $e)
        {
            Log::error($e);
            throw $e;
        }

        // 画像削除
        $filePath = 'public/products/' . $image->filename;
        if(Storage::exists($filePath)) Storage::delete($filePath);

        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => 'Success: Delete Image',
                'status' => 'alert',
            ]);
    }
}
