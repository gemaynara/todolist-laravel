<h1 align="center"> API - Gestão de usuários e lista de tarefas </h1>


[Live Demo](https://todolist-app-vue.herokuapp.com/)

> **Acesso admin:**
> 
> **email**: admin@admin.com
> 
> **senha**: secret

> **Acesso não admin:**
> 
> **email**: user@user.com
> 
> **senha**: secret
> 

## 🛠️ Para rodar o projeto

Instale as dependências
```bash
composer install 
```

Em seguida, ajustar o banco de dados a partir do arquivo .env.example


Criar todas as tabelas
```bash
php artisan migrate 
```


Criar um usuário via comando
```bash
php artisan user:add 
```

Rodar seeder para criar dados fakes de tarefas(opcional)
```bash
php artisan db:seeder 
```

Iniciar o servidor
```bash
php artisan serve
```



### 🛠 Tecnologias

As seguintes ferramentas foram usadas na construção do projeto:

- [PHP v7.4](https://www.php.net/releases/7_4_0.php)
- [Laravel v8](https://laravel.com/docs/8.x/releases)



## Autor

[<img src="https://avatars.githubusercontent.com/u/45495061?v=4" width=115><br><sub>Geanne Santos</sub>](https://github.com/gemaynara) 
