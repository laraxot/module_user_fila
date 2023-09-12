<?php

declare(strict_types=1);

namespace Modules\User\Jobs;

use Illuminate\Bus\Batchable;
use Spatie\PersonalDataExport\Jobs\CreatePersonalDataExportJob as BaseCreatePersonalDataExportJob;

final class CreatePersonalDataExportJob extends BaseCreatePersonalDataExportJob
{
    use Batchable;
}
