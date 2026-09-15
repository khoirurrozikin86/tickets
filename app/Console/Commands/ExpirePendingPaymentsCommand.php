<?php

namespace App\Console\Commands;

use App\Domain\Payments\Actions\ExpirePendingPaymentsAction;
use Illuminate\Console\Command;

class ExpirePendingPaymentsCommand extends Command
{
    protected $signature = 'payments:expire';

    protected $description = 'Expire pending payments and related orders';

    public function handle(
        ExpirePendingPaymentsAction $action
    ): int {
        $count = $action->execute();

        $this->info(
            "Expired {$count} payment(s)."
        );

        return self::SUCCESS;
    }
}
