<?php

namespace Viaativa\Viaroot\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
class PageBlock extends \Pvtl\VoyagerPageBlocks\PageBlock
{

    protected $touches = [
        'page',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'is_hidden' => 'boolean',
        'is_minimized' => 'boolean',
        'is_delete_denied' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'path',
        'data',
        'is_hidden',
        'is_minimized',
        'is_delete_denied',
        'cache_ttl',
    ];

    public function cacheKey()
    {
        return sprintf(
            "%s/%s-%s",
            $this->getTable(),
            $this->getKey(),
            $this->updated_at->timestamp
        );
    }

    public function page()
    {
        return $this->belongsTo('Viaativa\Viaroot\Models\Page');
    }

    // Fetch config for block template
    public function template()
    {
        if ($this->type === 'include') {
            return (object)[
                'template' => $this->type,
                'fields' => [],
            ];
        }

        $templateKey = $this->path;
        $templateConfig = json_encode(Config::get("page-blocks.$templateKey"));

        return json_decode($templateConfig);
    }

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    public function getCachedDataAttribute()
    {
        return Cache::remember($this->cacheKey() . ':datum', $this->cache_ttl, function () {
            return $this->data;
        });
    }
}
