<?php

use Carbon\Carbon;

function formatDate($date) {
    return Carbon::parse($date)->format(config('app.date_format'));
}
