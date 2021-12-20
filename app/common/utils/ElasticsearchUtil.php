<?php
/*
 * @Author: lpc
 * @DateTime: 2021/12/8 18:51
 * @Notes: Description
 * 
 * @return 

 */

namespace app\common\utils;


use Elasticsearch\ClientBuilder;

class ElasticsearchUtil
{
    public $index;
    public $type;
    public $client;

    public function __construct($index, $type = null)
    {
        $this->index = $index;
        $this->type = $type;

        //获取tp缓存redis配置信息
        $host = env('elasticsearch.host', '');
        $port = env('elasticsearch.port', '');
        $hosts = ["$host:$port"];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

    /**
     * 创建索引
     */
    public function createIndex(array $body): array
    {
        $params = [
            'index' => $this->index,
            'body' => $body
        ];

        return $this->client->indices()->create($params);
    }

    /**
     * 添加文档
     */
    public function createDocument($id, $data)
    {
        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'id' => $id,
            'body' => $data
        ];

        return $this->client->index($params);
    }

    /**
     * 批量添加文档
     */
    public function batchCreateDoc($data)
    {
        foreach ($data as $value) {
            $params = [
                'index' => $this->index,
                'type' => $this->type,
                'body' => $value
            ];
            $responses = $this->client->index($params);
        }

    }

    /**
     * 查询文档
     */
    public function getDocument($id)
    {
        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'id' => $id,
            'client' => ['ignore' => [400, 404]],//忽略查询状态码
        ];

        return $this->client->get($params);
    }

    /**
     * 搜索文档
     */
    public function searchDocument($body)
    {

        $params = [
            'index' => $this->index,
            'type' => $this->type,

            'body' => $body
        ];

        return $this->client->search($params);
    }

    /**
     * @param $keyword
     * @return array|callable
     * @desc 关键词搜索文档
     */
    public function keywordSearch($keyword)
    {
        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $keyword,
                    ],
                ]
            ]
        ];
        return $this->client->search($params);
    }

    /**
     * 删除索引
     */
    public function deleteIndex(): array
    {
        $params = ['index' => $this->index];
        $response = $this->client->indices()->delete($params);
        return $response;

    }
}