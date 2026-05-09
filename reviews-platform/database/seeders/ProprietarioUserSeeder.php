<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProprietarioUserSeeder extends Seeder
{
    /**
     * Cria usuário proprietário (iago@iago / 123456) se ainda não existir.
     * Usa firstOrCreate para não sobrescrever senha em cada deploy.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'iago@iago'],
            [
                'name' => 'Proprietário',
                'password' => Hash::make('123456'),
                'role' => 'proprietario',
                'payment_required' => false,
            ]
        );

        $this->command->info('Usuário proprietário garantido (criado se não existia).');
        $this->command->info('Email: iago@iago');
        $this->command->info('Senha: 123456');
    }
}
