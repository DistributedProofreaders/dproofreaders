<?php

// Prune non_activated_users entries
class PruneNonActivatedUsers extends BackgroundJob
{
    public function work()
    {
        NonactivatedUser::prune(90); // ~3 months
    }
}
