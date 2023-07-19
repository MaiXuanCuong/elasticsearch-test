<?php

namespace Database\Seeders;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ElasticsearchTable extends Seeder
{
    private $username;
    private $password;
    public function __construct()
    {
        $this->username = 'elastic';
        $this->password = 'UjklU64kBOlLSPrre6FT';
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = ClientBuilder::create()
            ->setHosts([
                'http://localhost:9200', // Hoặc 'https://localhost:9200' nếu bạn đang sử dụng SSL
            ])
            ->setBasicAuthentication($this->username, $this->password)
            ->build();
        $arrayTable = ['products','categories','users','services','sensors'];
        // Kiểm tra xem chỉ mục đã tồn tại hay chưa
        foreach ($arrayTable as $value) {
            // if (!$client->indices()->exists(['index' => $value])) {
                // Nếu chỉ mục chưa tồn tại, tạo mới chỉ mục và thiết lập các thiết lập và mappings
                $params = [
                    'index' => $value,
                    'body' => [
                        'settings' => [
                            'analysis' => [
                                'analyzer' => [
                                    'custom_ngram' => [
                                        'tokenizer' => 'custom_ngram_tokenizer'
                                    ]
                                ],
                                'tokenizer' => [
                                    'custom_ngram_tokenizer' => [
                                        'type' => 'ngram',
                                        'min_gram' => 1,
                                        'max_gram' => 10, // Thay đổi giá trị max_gram thành 10
                                        'token_chars' => ['letter', 'digit']
                                    ]
                                ]
                            ]
                        ],
                        'mappings' => [
                            'properties' => [
                                'name' => [
                                    'type' => 'text',
                                    'analyzer' => 'custom_ngram'
                                ]
                            ]
                        ]
                    ]
                ];
    
                $response = $client->indices()->create($params);
            }
        // }
    }
}
