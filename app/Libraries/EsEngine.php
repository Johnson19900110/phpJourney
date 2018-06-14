<?php
/**
 * Created by PhpStorm.
 * User: johnson
 * Date: 2018/6/14
 * Time: 下午3:10
 */

namespace App\Libraries;


use Laravel\Scout\Builder;
use ScoutEngines\Elasticsearch\ElasticsearchEngine;
use Illuminate\Database\Eloquent\Collection;

class EsEngine extends ElasticsearchEngine
{
    public function search(Builder $builder)
    {
        return $this->performSearch($builder, array_filter([
            'numericFilters' => $this->filters($builder),
            'size' => $builder->limit,
        ]));
    }

    protected function performSearch(Builder $builder, array $options = [])
    {
        $params = [
            'index' => $this->index,
            'type' => $builder->model->searchableAs(),
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'query_string' => [
                                    'query' => "*{$builder->query}*",
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ];
        /**
         * 这里使用了 highlight 的配置
         */
        if ($builder->model->searchSettings
            && isset($builder->model->searchSettings['attributesToHighlight'])
        ) {
            $attributes = $builder->model->searchSettings['attributesToHighlight'];
            foreach ($attributes as $attribute) {
                $params['body']['highlight']['fields'][$attribute] = new \stdClass();
            }
        }

        if ($sort = $this->sort($builder)) {
            $params['body']['sort'] = $sort;
        }

        if (isset($options['from'])) {
            $params['body']['from'] = $options['from'];
        }

        if (isset($options['size'])) {
            $params['body']['size'] = $options['size'];
        }

        if (isset($options['numericFilters']) && count($options['numericFilters'])) {
            $params['body']['query']['bool']['must'] = array_merge($params['body']['query']['bool']['must'],
                $options['numericFilters']);
        }

        return $this->elastic->search($params);
    }

    public function map($results, $model)
    {
        if ($results['hits']['total'] === 0) {
            return Collection::make();
        }

        $keys = collect($results['hits']['hits'])
            ->pluck('_id')->values()->all();

        $models = $model->whereIn(
            $model->getKeyName(), $keys
        )->get()->keyBy($model->getKeyName());

        return collect($results['hits']['hits'])->map(function ($hit) use ($model, $models) {

            $one = $models[$hit['_id']];
            /**
             * 这里返回的数据，如果有 highlight，就把对应的  highlight 设置到对象上面
             */
            if (isset($hit['highlight'])) {
                $one->highlight = $hit['highlight'];
            }
            return $one;
        });
    }
}