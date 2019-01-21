# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Convenções de teste da aplicação

## Estrutura de diretórios
- Todos os testes unitários devem ser colocados no diretório `tests/Unit`
- Todos os teste de integração devem ser colocados no diretório `tests/Feature`
- Conteúdos dos diretórios de testes `Unit` e `Feature` devem conter a mesma estrutura de do diretório `app`. Por exemplo, teste unitário para o arquivo `app/Models/User.php` deve ser escrito em `tests/Unit/Models/UserTest.php`

## Convenção de nome
- Cada arquivo de teste deve ter um *namespace* específico. O nome deve começar com `Tests\` (ou `ASampleProjectTests\`), então seguido pela estrutura de diretórios. Por exemplo, o *namespace pra o arquivo * `tests/Unit/Models/UserTest.php` deve ser `Tests\Unit\Models\UserTest` (ou `ASampleProjectTests\Unit\Models\UserTest`)
- Para melhor legibilidade, use `snake_case` para nomear as funções de teste. Uma função de teste de começar com `test`, veja abaixo um exemplo:

```
public function test_it_throws_an_exception_when_email_is_too_long()
{
}
```

## Componentes obrigatórios do teste unitário
- **Controllers**: com manuseio de eventos desabilitados. Todos os componentes externos DEVEM ser *mocked*.
- **Requests** (se presente): testar a validação
- **Models**: getters, setters, funcionalidades adicionais
- **Transformers / Presenters** (se presente): testar resultados de saída para diferentes conjuntos de fontes
- **Repositories** (se presente): testar cada método para criar consultas SQL corretas OU corrigir chamadas para o construtor de consultas de simulação
- **Event listeners**
- **Queue jobs**
- **Auth policies**
- E quaisquer classes adicionais específicas do projeto

## Componentes obrigatórios do teste de integração
- **Routes**: testar input/output com integração em todo um sistema
- **Route authentication**

## Cobertura de código
- Cobertura de código para todo o projeto deve ser maior do que `80%`
