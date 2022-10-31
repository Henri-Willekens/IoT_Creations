<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function getPageInfos() {
        $page=1;
        $itemPerPage = 8;
        $orderBy = 'id';
        $order = 'desc';
        $query = NULL;

        if (isset($_GET['query'])) {
            $query = $_GET['query'];
        }

        if (isset($_GET['page']) && is_numeric($_GET['page']) && intval($_GET['page']) >= 1) {
            $page=$_GET['page'];
        }

        if (isset($_GET['limit']) && is_numeric($_GET['limit']) && intval($_GET['limit']) >= 1) {
            $itemPerPage=$_GET['limit'];
        }

        if (isset($_GET['order'])) {
            $orderList = array('asc', 'desc');
            if (in_array($_GET['order'], $orderList)) {
                $order = $_GET['order'];
            }
        }

        if (isset($_GET['orderBy'])) {
            $orderByList = array('id', 'name', 'price');
            if (in_array($_GET['orderBy'], $orderByList)) {
                $orderBy = $_GET['orderBy'];
            }
        }

        return [
            "query" => $query,
            "order" => $order,
            "orderBy" => $orderBy,
            "page" => $page,
            "itemPerPage" => $itemPerPage,
        ];
    }

    function getProducts() {
        # Get GET parameters
        $pageInfo = $this->getPageInfos();
        $search = $pageInfo["query"];
        $page = $pageInfo["page"] - 1;
        $order = $pageInfo["order"];
        $orderBy = $pageInfo["orderBy"];
        $itemPerPage = $pageInfo["itemPerPage"];

        if ($search == NULL) {
            $totalItemCount = Product::count();
        } else{
            $totalItemCount = Product::where('name', 'LIKE', '%'.$search.'%')->count();
        }
        $pageCount = ceil((float)$totalItemCount / (float)$itemPerPage);

        $itemStartIdx = $itemPerPage * $page;

        // Start too high : reset to zero
        if ($itemStartIdx >= $totalItemCount) {
            $itemStartIdx = 0;
            $page = 0;
        }


        $itemEndIdx = $itemStartIdx + $itemPerPage;
        if  ($itemEndIdx > $totalItemCount) {
            $itemEndIdx = $totalItemCount ;
        }

        $itemCounts = $itemEndIdx - $itemStartIdx;

        if ($search == NULL) {
            $products = Product::orderBy($orderBy, $order)->skip($itemStartIdx)->take($itemCounts)->get();
        } else {
            $products = Product::where('name', 'LIKE', '%'.$search.'%')->orderBy($orderBy, $order)->skip($itemStartIdx)->take($itemCounts)->get();
        }

        return array(
            "products" => $products,
            "page_count" => $pageCount,
            "current_page" => $page + 1,
            "orderBy" => $orderBy,
            "order" => $order,
            "itemPerPage" => $itemPerPage,
            "totalItemCount" => $totalItemCount,
        );
    }

    public function index()
    {
        $viewData = [];
        $viewData["title"] = "Products - Online Store";
        $viewData["subtitle"] =  "List of products";

        $productList = $this->getProducts();
        if ($productList["totalItemCount"] > 0)
            $viewData["subtitle"] = $viewData["subtitle"]."- Page ".$productList["current_page"];
        else
            $viewData["subtitle"] = $viewData["subtitle"]."- No results";

        $view = view('product.index')->with("viewData", $viewData);

        foreach ($productList as $key => $value)
        {
            $view = $view->with($key, $value);
        }

        return $view;
    }

    public function show($id)
    {
        $viewData = [];
        $product = Product::findOrFail($id);
        $viewData["title"] = $product->getName()." - Online Store";
        $viewData["subtitle"] =  $product->getName()." - Product information";
        $viewData["product"] = $product;

        return view('product.show')->with("viewData", $viewData);
    }
}
