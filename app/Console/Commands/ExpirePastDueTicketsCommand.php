<?php

namespace App\Console\Commands;

use App\Domain\Tickets\Actions\ExpirePastDueTicketsAction;
use Illuminate\Console\Command;

class ExpirePastDueTicketsCommand extends Command
{
    protected $signature = 'tickets:expire';

    protected $description = 'Expire active tickets whose visit date has passed';

    public function handle(
        ExpirePastDueTicketsAction $action
    ): int {
        $count = $action->execute();

        $this->info(
            "Expired {$count} ticket(s)."
        );

        return self::SUCCESS;
    }
}
