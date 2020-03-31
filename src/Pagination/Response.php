<?php

namespace App\Pagination;

/**
 * @author Alexander Tsukanov <https://alexdevid.com>
 */
class Response
{
    public const LIMIT = 20;
    public const ORDER_ASC = 'asc';
    public const ORDER_DESC = 'desc';
    public const ORDER_BY_DEFAULT = 'id';

    public const PARAM_PAGE = 'page';
    public const PARAM_LIMIT = 'limit';
    public const PARAM_ORDER = 'order';
    public const PARAM_ORDER_BY = 'order_by';

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var int
     */
    public $limit = self::LIMIT;

    /**
     * @var string
     */
    public $order = self::ORDER_ASC;

    /**
     * @var string
     */
    public $order_by = 'id';

    /**
     * @var int
     */
    public $total = 0;

    /**
     * @var array
     */
    public $collection = [];

    public function __construct(int $page = 1, int $limit = self::LIMIT, string $orderBy = self::ORDER_BY_DEFAULT, string $order = self::ORDER_ASC)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->order_by = $orderBy;
        $this->order = $order;
    }

    public static function createFromRequestData(array $request)
    {
        return new Response(
            $request[self::PARAM_PAGE] ?? 1,
            $request[self::PARAM_LIMIT] ?? self::LIMIT,
            $request[self::PARAM_ORDER_BY] ?? self::ORDER_BY_DEFAULT,
            $request[self::PARAM_ORDER] ?? self::ORDER_ASC
        );
    }
}