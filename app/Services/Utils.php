<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Utils
{
    public static function paginate_without_key($items, $page = null, $options = [])
    {

        $perPage = 5;

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        $lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        return [
            'current_page' => $lap->currentPage(),
            'data' => $lap ->values(),
            'last_page' => $lap->lastPage(),
            'next_page_url' => $lap->nextPageUrl(),
            'prev_page_url' => $lap->previousPageUrl(),
        ];

    }
}
