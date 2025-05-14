<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/06/2023
 * @copyright NIP CIBER-LAB @2023
 */

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
