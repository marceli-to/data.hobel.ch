<?php

use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/database', \App\Mcp\Servers\DatabaseQuery::class);
