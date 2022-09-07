<?php

namespace domain\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductService
{

    protected $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function all()
    {
        $products = Product::all();
        return $products;
    }

    public function store($data)
    {
        if ($data["image"] != null) {

            $file = $data["image"];
            $ext = $file->getClientOriginalExtension();
            $filename  = time() . "." . $ext;
            $file->move('assets/images/', $filename);
            $data["image"] = $filename;
        }

        if (in_array("status", $data)) {
            if ($data["status"] == "on") {
                $data["status"] = 1;
            }
        } else {
            $data["status"] = 0;
        }
        $this->product->create($data);
    }

    public function update($data, $product_id)
    {
        $product = $this->product->find($product_id);
        $update_data = $data->toArray();

        // dd($update_data);

        array_push($update_data, $product_id);
        if (isset($update_data["image"])) {
            $path = 'assets/images/' . $product->image;

            if (File::exists($path)) {
                File::delete($path);
            }

            $file = $update_data["image"];
            $ext = $file->getClientOriginalExtension();
            $filename  = time() . "." . $ext;
            $file->move('assets/images/', $filename);
            $update_data["image"] = $filename;
        }



        if (in_array("status", $update_data)) {
            if ($update_data["status"] == "on") {
                $update_data["status"] = 1;
            }
        } else {
            $update_data["status"] = 0;
        }

        $product->update($update_data);
    }


    public function destroy($product_id)
    {
        $product = $this->product->find($product_id);
        $path = 'assets/images/' . $product->image;

        if (File::exists($path)) {
            File::delete($path);
        }
        $product->delete();
    }

    public function status($product_id)
    {
        $product = $this->product->find($product_id);

        if ($product->status == 1) {
            $product->status = 0;
        } else {
            $product->status = 1;
        }

        $product->update();
    }
}
