<?php
// To test that deprecated messages are detected correctly.
trigger_error("Oh my, that was so PHP 7.x of you", E_USER_DEPRECATED);
