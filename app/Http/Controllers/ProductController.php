<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $username;
    private $password;
    public function __construct()
    {
        $this->username = 'elastic';
        $this->password = 'UjklU64kBOlLSPrre6FT';
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);

        // Tạo sản phẩm trong cơ sở dữ liệu MySQL
        $product = Product::create($request->only(['name', 'description', 'price']));

        // Lưu thông tin sản phẩm vào Elasticsearch
        $client = ClientBuilder::create()
            ->setHosts([
                'http://localhost:9200', // Hoặc 'https://localhost:9200' nếu bạn đang sử dụng SSL
            ])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => 'products', // Tên chỉ mục Elasticsearch tương ứng với model Product
            'id' => $product->id,
            'body' => [
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
            ],
        ];

        $response = $client->index($params);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);

        // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu MySQL
        $product->update($request->only(['name', 'description', 'price']));

        // Cập nhật thông tin sản phẩm trong Elasticsearch
        $client = ClientBuilder::create()
            ->setHosts([
                'http://localhost:9200', // Hoặc 'https://localhost:9200' nếu bạn đang sử dụng SSL
            ])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();


        $params = [
            'index' => 'products', // Tên chỉ mục Elasticsearch tương ứng với model Product
            'id' => $product->id,
            'body' => [
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
            ],
        ];

        $response = $client->index($params);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $client = ClientBuilder::create()
            ->setHosts([
                'http://localhost:9200', // Hoặc 'https://localhost:9200' nếu bạn đang sử dụng SSL
            ])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => 'products', // Tên chỉ mục Elasticsearch tương ứng với model Product
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['name', 'description'],
                    ],
                ],
            ],
        ];

        $response = $client->search($params);
        $products = collect($response['hits']['hits'])->map(function ($hit) {
            $source = $hit['_source'];
            $source['id'] = $hit['_id'];
            return (object)$source;
        });

        return view('products.index', compact('products'));
    }

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function destroy(Product $product)
    {
        // Xóa sản phẩm trong cơ sở dữ liệu MySQL
        $product->delete();

        // Xóa sản phẩm trong Elasticsearch
        $client = ClientBuilder::create()
            ->setHosts([
                'http://localhost:9200', // Hoặc 'https://localhost:9200' nếu bạn đang sử dụng SSL
            ])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => 'products', // Tên chỉ mục Elasticsearch tương ứng với model Product
            'id' => $product->id, // Lấy id của sản phẩm để xóa trong Elasticsearch
        ];

        $response = $client->delete($params);

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
    
    public function showDocs($index)
    {
        $client = ClientBuilder::create()
            ->setHosts([
                'http://localhost:9200', // Hoặc 'https://localhost:9200' nếu bạn đang sử dụng SSL
            ])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
            'body' => [
                'query' => [
                    'match_all' => (object)[]
                ]
            ]
        ];

        $response = $client->search($params);

        $docs = collect($response['hits']['hits'])->pluck('_source');

        return view('indexes.show', compact('index', 'docs'));
    }
    public function destroyIndex($index)
    {
        $client = ClientBuilder::create()
            ->setHosts([
                'http://localhost:9200', // Hoặc 'https://localhost:9200' nếu bạn đang sử dụng SSL
            ])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
        ];

        $response = $client->indices()->delete($params);

        return redirect()->route('indexes.index')->with('success', 'Index deleted successfully.');
    }
    public function destroyDoc($index, $docId)
    {
        $client = ClientBuilder::create()
            ->setHosts([
                'http://localhost:9200', // Hoặc 'https://localhost:9200' nếu bạn đang sử dụng SSL
            ])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
            'id' => $docId,
        ];

        $response = $client->delete($params);

        return redirect()->route('indexes.show', ['index' => $index])->with('success', 'Document deleted successfully.');
    }
}
