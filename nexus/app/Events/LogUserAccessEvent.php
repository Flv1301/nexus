<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class LogUserAccessEvent
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
