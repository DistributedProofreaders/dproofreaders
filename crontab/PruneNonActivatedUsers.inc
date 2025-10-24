<?php

// Prune non_activated_users entries
class PruneNonActivatedUsers extends BackgroundJob
{
    public function work()
    {
        NonactivatedUser::prune(SiteConfig::get()->days_to_retain_pending_accounts);
    }
}
