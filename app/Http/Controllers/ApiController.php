<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\customer;
use App\Models\inventory;
use App\Models\product;
use App\Models\varian_product;
use App\Models\group_product;
use App\Models\pembelian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Laravel\Sanctum\PersonalAccessToken;

class ApiController extends Controller
{
    //
    public function index()
    {
        // return view('sales.index');
    }

    public function cekToken($token='')
    {
        // $bearerToken = "client-w8Bol1DFoA6QnrtBc0ic885Sh8iyAbWnvsrmnE196bad5a8f";
        $bearerToken = $token;

        $token = PersonalAccessToken::findToken($bearerToken);

        if (!$token) {
            return 0;
        }
        return 1;
    }

    public function getInventories(Request $request)
    {
        $token = $this->cekToken($request->key);
        if ($token == 0) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Unauthorized Access.'
            ], 400);
        }
        $arr = array();
        $inven = inventory::where('status','1')->get();
        foreach ($inven as $key => $value) {
            $arrTemp = array();
            $arrTemp['name'] = $value->nama;
            $arrTemp['price'] = $value->price;
            $arrTemp['amount'] = $value->amount;
            $arrTemp['unit'] = $value->unit;
            array_push($arr, $arrTemp);
        }
        return response()->json([
            'status_code' => 200,
            'data' => $arr,
            'total' => $inven->count()
        ],200);
    }

    public function getProducts(Request $request)
    {
        $token = $this->cekToken($request->key);
        if ($token == 0) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Unauthorized Access.'
            ], 400);
        }
        $arr = array();
        $product = product::where('status','1')->get();
        foreach ($product as $key => $value) {
            $arrTemp = array();
            $arrVarianTemp = array();
            $varian = group_product::where('product_id',$value->id)->get();
            foreach ($varian as $key1 => $value1) {
                $arrVarian = array();
                $arrVarian['name'] = $value1->variant->nama;
                $arrVarian['additional_price'] = $value1->variant->price;
                array_push($arrVarianTemp,$arrVarian);
            }
            $arrTemp['id'] = $value->id;
            $arrTemp['name'] = $value->nama;
            $arrTemp['description'] = $value->deskripsi;
            $arrTemp['price'] = $value->price;
            $arrTemp['variants'] = $arrVarianTemp;
            array_push($arr, $arrTemp);
        }
        return response()->json([
            'status_code' => 200,
            'data' => $arr,
            'total' => $product->count()
        ],200);
    }

    public function getSales(Request $request)
    {
        $token = $this->cekToken($request->key);
        if ($token == 0 || !$request->ID) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Unauthorized Access.'
            ], 400);
        }
        $arr = array();
        $arrTemp = array();
        $cus = customer::find($request->ID);
        $pembelian = DB::table('pembelians')
        ->leftJoin('group_products', 'pembelians.grup_product_id', 'group_products.id')
        ->leftJoin('products', 'group_products.product_id', 'products.id')
        ->where('pembelians.customer_id',$cus->id)
        ->selectRaw('products.id, products.price, products.nama, group_concat(group_products.id) as grup_product_id')
        ->groupBy('products.id', 'products.price', 'products.nama')
        ->distinct()
        ->get();
        $total = 0;
        foreach ($pembelian as $key => $value) {
            $arrVarianTemp = array();
            $arrPembelianTemp = array();
            $group_product = group_product::whereIn('id',explode(',',$value->grup_product_id))->get();
            foreach ($group_product as $key1 => $value1) {
                $arrVarian = array();
                $arrVarian['name'] = $value1->variant->nama;
                $arrVarian['additional_price'] = $value1->variant->price;
                $total += $value1->variant->price;
                array_push($arrVarianTemp,$arrVarian);
            }
            $arrPembelianTemp['product_id'] = $value->id;
            $arrPembelianTemp['name'] = $value->nama;
            $arrPembelianTemp['price'] = $value->price;
            $arrPembelianTemp['variants'] = $arrVarianTemp;
            $total += $value->price;
            array_push($arrTemp, $arrPembelianTemp);
            
        }
        $arr['id'] = $cus->id;
        $arr['cart'] = $arrTemp;
        $arr['total'] = $total;
        $arr['created'] = date('d F Y h:i:s', strtotime($cus->created_at));
        $arr['payment_method'] = $cus->payment_method;

        return response()->json([
            'status_code' => 200,
            'data' => $arr
        ],200);
    }

    public function buatToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Email & password salah!'
            ],422);
        }
        $token = $user->createToken($request->device_name)->plainTextToken;
        return response()->json([
            'status_code' => 200,
            'token' => $token
        ],200);
    }
}
