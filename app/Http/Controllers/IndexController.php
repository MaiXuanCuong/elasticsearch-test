<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $username;
    private $password;
    public function __construct()
    {
        $this->username = 'elastic';
        $this->password = 'UjklU64kBOlLSPrre6FT';
    }
    public function showIndexes()
    {
        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        $response = $client->cat()->indices(['format' => 'json']);
        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            $indexes = collect(json_decode($response->getBody(), true))->pluck('index');

            return view('indexes.index', compact('indexes'));
        }
    }

    public function showIndex($index)
    {
        // Lấy danh sách tài liệu từ Elasticsearch theo index
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

        $documents = collect($response['hits']['hits'])->pluck('_source');
        return view('indexes.show', compact('index', 'documents'));
    }
    public function destroy($index)
    {
        $client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();

        // Xóa chỉ mục
        $response = $client->indices()->delete(['index' => $index]);

        return redirect()->route('indexes.index')->with('success', 'Index deleted successfully.');
    }
}
