<?php

namespace PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners;

 use Exception;
use Illuminate\Contracts\Queue\ShouldQueue; 

class TenantRegisteringListener implements ShouldQueue
{
 
    protected function generateCompanyIdString($event): self
    {
        $event->tenant->generateCompanyIdString();
        $event->tenant->save();
        return $this;
    }

    /**
     * Handle the event.
     *
     * @param Object $event
     * @return void
     * @throws Exception
     */
    public function handle($event)
    {
        $this->generateCompanyIdString($event);
    }
}
