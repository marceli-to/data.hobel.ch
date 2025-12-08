<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\QueryProducts;
use App\Mcp\Tools\GetStatistics;
use App\Mcp\Resources\DatabaseSchema;
use Laravel\Mcp\Server;

class DatabaseQuery extends Server
{
    /**
     * The MCP server's name.
     */
    protected string $name = 'Product Database Query';

    /**
     * The MCP server's version.
     */
    protected string $version = '1.0.0';

    /**
     * The MCP server's instructions for the LLM.
     */
    protected string $instructions = <<<'MARKDOWN'
        This MCP server provides access to a product catalog database. You can:
        
        1. **Query Products** - Search and filter products by category, tag, price range, stock status, etc.
        2. **Get Statistics** - Get an overview of the database including counts, averages, and breakdowns.
        3. **View Schema** - Access the database schema to understand the data structure.
        
        Example queries you can answer:
        - "How many products are in stock?"
        - "Show me all featured products"
        - "What products are in the 'Electronics' category?"
        - "Find products priced between $10 and $50"
        - "What's the average product price?"
        - "Which categories have the most products?"
    MARKDOWN;

    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        QueryProducts::class,
        GetStatistics::class,
    ];

    /**
     * The resources registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Resource>>
     */
    protected array $resources = [
        DatabaseSchema::class,
    ];

    /**
     * The prompts registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Prompt>>
     */
    protected array $prompts = [
        //
    ];
}
