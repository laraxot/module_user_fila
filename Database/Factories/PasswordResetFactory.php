<<<<<<< HEAD
<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\PasswordReset;

class PasswordResetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = PasswordReset::class;

    /**
     * Define the model's default state.
     *
     * @return array<DateTime|string>
     *
     * @psalm-return array{email: string, token: string, created_at: \DateTime}
     */
    public function definition()
    {
        return [
            'email' => $this->faker->email,
            'token' => $this->faker->word,
            'created_at' => $this->faker->dateTime,
        ];
    }
}
=======
=======
>>>>>>> c3ef5a0 (up)
<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use Modules\User\Models\PasswordReset;

class PasswordResetFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = PasswordReset::class;

    /**
     *
     * Define the model's default state.
     *
     * @return (\DateTime|string)[]
     *
     * @psalm-return array{email: string, token: string, created_at: \DateTime}
     */
    public function definition() {


        return [
            'email' => $this->faker->email,
            'token' => $this->faker->word,
            'created_at' => $this->faker->dateTime
        ];
    }
}
<<<<<<< HEAD
>>>>>>> d1783f5 (up)
=======
>>>>>>> c3ef5a0 (up)
