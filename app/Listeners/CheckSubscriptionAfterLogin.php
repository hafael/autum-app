<?php

namespace App\Listeners;


use App\Services\AutumPlatformService;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class CheckSubscriptionAfterLogin
{

    protected $service;
    protected $stripeProductId;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AutumPlatformService $service)
    {
        $this->service = $service;
        $this->stripeProductId = env('STRIPE_PRODUCT_ID');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {

        try {
            $subscription = $this->service->actingAsUser($event->user)
                                ->subscriptions()
                                ->showById($this->stripeProductId);

        } catch (\Exception $e) {

            $this->failed($event, $e);
            return;

        }

        $this->updateSubscription($event->user, $subscription);

    }

    /**
     * Handle a job failure.
     */
    public function failed(Login $event, Throwable $exception): void
    {
        Log::critical('CheckSubscriptionAfterLogin failed', [
            'user' => $event->user->id,
            'exception' => $exception->getMessage(),
        ]);
    }

    private function updateSubscription($user, $subscription)
    {

        $subscription = $subscription ? $subscription->json('data') : null;

        if(!$subscription || $subscription['stripe_status'] !== 'active') {
            $this->disableSubscription($user);
            return;
        }

        $item = collect($subscription['items'])->firstWhere(function($i) {
            return $i['stripe_product'] == $this->stripeProductId;
        });

        $user->update([
            'subscription_id' => $subscription['id'],
            'blocked_at' => null,
            'spend_limit_code' => Str::slug($item['price']['nickname']),
        ]);
    }

    private function disableSubscription($user)
    {
        $user->update([
            'blocked_at' => now()->toDateTimeString(),
        ]);
    }
}
