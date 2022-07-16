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
    protected $signature = 'user:add {name?} {email?}';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Insira os dados para criar um novo usuário');

        $name = $this->argument('name');

        if (empty($name)) {
            $name = $this->ask('Seu nome');
        }

        $email = $this->argument('email');
        if (empty($email)) {
            $email = $this->ask('Seu e-mail');
        }

        $password = $this->secret('Senha');
        $confirmed = $this->secret('Repita a senha');

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'confirmed' => $confirmed
        ];

        try {
            $validator = Validator::make($data, [
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'min:6', 'same:confirmed'],
            ]);

            if ($validator->fails()) {
                $this->error('Erro ao criar usuário:');
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }
                return false;
            }

            $data['password'] = Hash::make($password);
            User::query()->create($data);

            $this->info('Usuário criado com sucesso!');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
