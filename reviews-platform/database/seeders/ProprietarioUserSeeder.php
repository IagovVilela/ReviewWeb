<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProprietarioUserSeeder extends Seeder
{
    /**
     * Cria usuário proprietário (iago@iago / 123456).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'iago@iago'],
            [
                'name' => 'Proprietário',
                'password' => Hash::make('123456'),
                'role' => 'proprietario',
                'payment_required' => false,
            ]
        );

        $this->command->info('Usuário proprietário criado/atualizado.');
        $this->command->info('Email: iago@iago');
        $this->command->info('Senha: 123456');
    }
}
