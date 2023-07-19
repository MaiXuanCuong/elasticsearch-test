<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Sleep;

class DocumentController extends Controller
{
    private $username;
    private $password;
    public function __construct()
    {
        $this->username = 'elastic';
        $this->password = 'UjklU64kBOlLSPrre6FT';
    }
    // public function store(Request $request, $index)
    // {
    //     $request->validate([
    //         'name' => 'required|max:255',
    //         'description' => 'nullable',
    //         'price' => 'required|numeric',
    //     ]);

    //     // Tạo tài liệu mới trong Elasticsearch
    //     $client = ClientBuilder::create()
    //         ->setHosts(['http://localhost:9200'])
    //         ->setBasicAuthentication($this->username, $this->password)
    //         ->build();

    //     $params = [
    //         'index' => $index,
    //         'body' => [
    //             'name' => $request->input('name'),
    //             'description' => $request->input('description'),
    //             'price' => $request->input('price'),
    //         ],
    //     ];

    //     $response = $client->index($params);

    //     return redirect()->route('indexes.index', ['index' => $index])->with('success', 'Document created successfully.');
    // }

    // public function update(Request $request, $index, $id)
    // {
    //     $request->validate([
    //         'name' => 'required|max:255',
    //         'description' => 'nullable',
    //         'price' => 'required|numeric',
    //     ]);

    //     // Cập nhật thông tin tài liệu trong Elasticsearch
    //     $client = ClientBuilder::create()
    //         ->setHosts(['http://localhost:9200'])
    //         ->setBasicAuthentication($this->username, $this->password)
    //         ->build();

    //     $params = [
    //         'index' => $index,
    //         'id' => $id,
    //         'body' => [
    //             'doc' => [
    //                 'name' => $request->input('name'),
    //                 'description' => $request->input('description'),
    //                 'price' => $request->input('price'),
    //             ],
    //         ],
    //     ];

    //     $response = $client->update($params);

    //     return redirect()->route('indexes.index', ['index' => $index])->with('success', 'Document updated successfully.');
    // }

    // public function destroy($index, $id)
    // {
    //     // Xóa tài liệu trong Elasticsearch
    //     $client = ClientBuilder::create()
    //         ->setHosts(['http://localhost:9200'])
    //         ->setBasicAuthentication($this->username, $this->password)
    //         ->build();

    //     $params = [
    //         'index' => $index,
    //         'id' => $id,
    //     ];

    //     $response = $client->delete($params);

    //     return redirect()->route('indexes.index', ['index' => $index])->with('success', 'Document deleted successfully.');
    // }
    // // public function showDocByIndex($index)
    // // {
    // //     $client = ClientBuilder::create()
    // //         ->setHosts(['http://localhost:9200'])
    // //         ->setBasicAuthentication($this->username, $this->password)
    // //         ->build();

    // //     $params = [
    // //         'index' => $index,
    // //         'size' => 10, // Số lượng tài liệu tối đa hiển thị
    // //     ];

    // //     $response = $client->search($params);
    // //     $hits = $response['hits']['hits'];
        
    // //     return view('indexes.show', compact('index', 'hits'));
    // // }
    // public function edit($index, $id)
    // {
    //     // Lấy chi tiết tài liệu từ Elasticsearch
    //     $client = ClientBuilder::create()
    //         ->setHosts(['http://localhost:9200'])
    //         ->build();

    //     $params = [
    //         'index' => $index,
    //         'id' => $id,
    //     ];

    //     $response = $client->get($params);

    //     $document = $response['_source'];

    //     return view('documents.edit', compact('document'));
    // }
    public function index($index)
    {
        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ];

        $response = $client->search($params);

        $documents = collect($response['hits']['hits'])->map(function ($hit) {
            $source = $hit['_source'];
            $source['id'] = $hit['_id'];
            return $source;
        });

        return view('documents.index', compact('index', 'documents'));
    }

    public function show($index, $id)
    {
        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
            'id' => $id,
        ];

        $response = $client->get($params);

        $document = $response['_source'];

        return view('documents.show', compact('index', 'document'));
    }

    public function create($index)
    {
        return view('documents.create', compact('index'));
    }

    public function store(Request $request, $index)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);

        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
            'body' => [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
            ],
        ];

        $response = $client->index($params);
        Sleep::for(200)->milliseconds();
        return redirect()->route('documents.index', $index)->with('success', 'Document created successfully.');
    }

    public function edit($index, $id)
    {
        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
            'id' => $id,
        ];

        $response = $client->get($params);
        $document = $response['_source'];
        $document['id'] = $response['_id'];

        return view('documents.edit', compact('index', 'document'));
    }

    public function update(Request $request, $index, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);

        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
            'id' => $id,
            'body' => [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
            ],
        ];

        $response = $client->index($params);
        Sleep::for(200)->milliseconds();
        return redirect()->route('documents.index', $index)->with('success', 'Document updated successfully.');
    }

    public function destroy($index, $id)
    {
        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $params = [
            'index' => $index,
            'id' => $id,
        ];

        $response = $client->delete($params);
        Sleep::for(200)->milliseconds();
        return redirect()->route('documents.index', $index)->with('success', 'Document deleted successfully.');
    }
}
