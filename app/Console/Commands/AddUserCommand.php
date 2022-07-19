<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class AddUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que cria usuário';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return false
     */
    public function handle()
    {
        $this->info('Insira os dados para criar um novo usuário');

        $name = $this->ask('Seu nome');

        $email = $this->ask('Seu e-mail');

        $password = $this->secret('Senha');
        $confirmed = $this->secret('Repita a senha');

        $admin = $this->ask('Usuário admin? [s/n]');
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirm' => $confirmed,
            'is_admin' => $admin == 's' ?? false
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4|same:password_confirm',
            'is_admin' => 'required|boolean',
        ]);

            if ($validator->fails()) {
                $this->error('Erro ao criar usuário:');
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }
                return false;
            }

        try {
            $data['password'] = Hash::make($data['password']);
            User::query()->create($data);
            $this->info('Usuário criado com sucesso!');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
