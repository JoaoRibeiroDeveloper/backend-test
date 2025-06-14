<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class Retrieve extends BaseRepository
{
    /**
     * Id da empresa
     *
     * @var string
     */
    protected string $companyId;

    /**
     * Name
     *
     * @var string|null
     */
    protected ?string $name;

    /**
     * Email
     *
     * @var string|null
     */
    protected ?string $email;

    /**
     * Status
     *
     * @var string|null
     */
    protected ?string $status;

    /**
     * Setar a model do usuário
     *
     * @return void
     */
    public function setModel(): void
    {
        $this->model = User::class;
    }

    public function __construct(string $companyId, ?string $name, ?string $email, ?string $status)
    {
        $this->companyId = $companyId;
        $this->name      = $name;
        $this->email     = $email;
        $this->status    = $status;

        parent::__construct();
    }

    /**
     * Left join com accounts
     *
     * @return void
     */
    protected function leftJoinAccount(): void
    {
        $this->builder->leftJoin(
            'accounts',
            'accounts.user_id',
            '=',
            'users.id'
        );
    }

    /**
     * Lista de usuários (Paginado)
     *
     * @return LengthAwarePaginator
     */
    public function handle(): LengthAwarePaginator
    {
        $this->leftJoinAccount();


        // No código original, o uso do método whereRaw com concatenação direta de variáveis externas para montar consultas SQL apresenta um grave risco de SQL Injection. Ao inserir valores diretamente na string da query sem qualquer sanitização ou binding, a aplicação fica vulnerável a ataques que podem manipular a consulta, expondo ou comprometendo dados sensíveis.
        // Para mitigar essa vulnerabilidade, a melhor prática é utilizar os métodos de query builder do Laravel que aceitam bindings automáticos, como where com parâmetros, que previnem que os valores sejam interpretados como código SQL. Além disso, o uso de funções específicas como whereNull para verificar valores nulos torna o código mais legível e seguro.
        // A correção proposta substitui os whereRaw por métodos que lidam corretamente com parâmetros, garantindo a integridade, segurança e manutenibilidade do código, prevenindo erros críticos e possíveis ataques maliciosos.

        if ($this->name) {
            $this->builder->whereRaw("name LIKE '%" . $this->name . "%'");
        }

        if ($this->email) {
            $this->builder->whereRaw("email LIKE '%" . $this->email . "%'");
        }

        if ($this->status) {
            if ($this->status === 'INACTIVE') {
                $this->builder->whereRaw('accounts.id IS NULL');
            } else {
                $this->builder->whereRaw('accounts.status = "' . $this->status . '"');
            }
        }

        $this->builder->where('company_id', $this->companyId)
            ->orderBy('name');

        return $this->paginate(['users.*']);
    }
}
