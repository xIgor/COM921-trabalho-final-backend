# Configuração mínima para novos projetos em PHP

## Instalar o Docker e Docker Compose

- Docker (CE): https://docs.docker.com/install/linux/docker-ce/ubuntu/
- Docker Compose: https://docs.docker.com/compose/install/

## Execução
```sh
docker-compose up
```

Acesso de linha de comando para mysql e beanstalk
```sh
# se possuir o mysql instalado em sua máquina,
# caso contrário, utilize o método de **Informações Gerais**)
mysql -h 127.0.0.1 -u root -p --port=13306

# beanstalkd
telnet 127.0.0.1 21300
```

### Acessar containers:

```sh
docker exec -it unifeiapi bash
composer install    # instala dependências
```

```sh
docker exec -it unifeisql bash
mysql -u unifei -p unifei
# senha: unifei
 ```

 ### Aplicar patch do banco:
```sh
cat data/database/patches/base_patch.sql | docker exec -i unifeisql /usr/bin/mysql -u unifei --password=unifei unifei
 ```


### Execução de testes

**DENTRO DO CONTAINER DO PHP**

**Dica**: Para testes funcionais, crie uma classe por **_ACTION_**.

```sh
# Execução e observação de mudanças nas classes da aplicação e de teste
vendor/bin/phpunit-watcher watch
# Execução de todos os testes
vendor/bin/phpunit
# Execução de testes de uma classe específica
vendor/bin/phpunit --filter AccountCreateTest
# Execução de testes de uma classe específica exibindo mais detalhes
vendor/bin/phpunit --filter AccountCreateTest --testdox
# Execução de teste de um método específico
vendor/bin/phpunit --filter testCreateAccount
# Para suite de testes
vendor/bin/phpunit --testsuite f # testes funcionais
vendor/bin/phpunit --testsuite u # testes unitários
```

### Atualização do banco de dados

**DENTRO DO CONTAINER DO PHP**

Para evitar problemas de esquecimento de aplicação de patches, foi adicionado o [**phinx**](http://docs.phinx.org).

```
vendor/bin/phinx status
vendor/bin/phinx migrate
```

Para criar uma nova migração

**FORA DO CONTAINER DO PHP**

```
vendor/bin/phinx create NomeDaMigracao
```

### Validação de parâmetros

A validação de parâmetros enviados para rotas (via post, get, ...) é feita via middleware **src/Middleware/Validation**. Cada rota deve possuir um middleware responsável pela validação dos parâmetros enviados para as _actions_ (se possuir parâmetros). Evite fazer validações nas _actions_.

As validações são construídas tendo como base os pacotes:
- [Zend\Validator](https://docs.zendframework.com/zend-validator/) e;
- [Zend\Filter](https://docs.zendframework.com/zend-filter/).

No entanto, os filtros e validadores não são utilizados diretamente. Apenas o uso via [Zend\InputFilter\Factory](https://docs.zendframework.com/zend-inputfilter/) é encorajado.

Este site possui uma explicação bastante simples do uso: https://akrabat.com/standalone-usage-of-zend-inputfilter/

É importante ressaltar que como os parâmetros costumam se repetir em diferentes rotas (por exemplo, a senha nas rotas de login, criar e atualizar usuário), pode ser interessante criar configurações de validação separadas para reuso. As classes em **src/Validator/Config** têm este propósito.

Por último, como, além da validação, os dados passam por filtros, a forma como eles são recebidos pelas _actions_ muda um pouco:

```php

// forma sem validação
$params = $request->getParams();
// forma com validação
$params = $request->getAttribute('params');

```

### VSCode

- Plugins
  - php cs fixer (+ [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) instalado como global pelo composer e disponível para acesso via $PATH)
  - php debug
  - php intelephense
  - sass
  - vetur
  - vue 2 snippets
  - git history
  - eslint
  - editorconfig for vscode
  - dracula official (theme)
  - docker
  - php snippets from phpstorm

- User Settings

```json
{
    "editor.autoIndent": false,
    "workbench.statusBar.feedback.visible": false,
    "window.zoomLevel": -1,
    "editor.tabCompletion": true,
    "files.exclude": {
        "**/.git": true,
        "**/.svn": true,
        "**/.hg": true,
        "**/CVS": true,
        "**/.DS_Store": true
    },
    "editor.fontSize": 17,
    "editor.wordWrap": "on",
    "files.trimTrailingWhitespace": true,
    "diffEditor.ignoreTrimWhitespace": false,
    "php-cs-fixer.formatHtml": true,
    "workbench.colorTheme": "Dracula",
    "php.suggest.basic": false
}
```

- Keybindings
```json
[
    {
        "key": "ctrl+alt+u",
        "command": "editor.action.transformToUppercase",
        "when": "editorTextFocus"
    },
    {
        "key": "ctrl+alt+l",
        "command": "editor.action.transformToLowercase",
        "when": "editorTextFocus"
    },
    {
        "key": "ctrl+up",
        "command": "editor.action.copyLinesUpAction",
        "when": "editorTextFocus && !editorReadonly"
    },
    {
        "key": "ctrl+down",
        "command": "editor.action.copyLinesDownAction",
        "when": "editorTextFocus && !editorReadonly"
    },
    {
        "key": "ctrl+shift+down",
        "command": "editor.action.moveLinesDownAction",
        "when": "editorTextFocus && !editorReadonly"
    },
    {
        "key": "ctrl+shift+up",
        "command": "editor.action.moveLinesUpAction",
        "when": "editorTextFocus && !editorReadonly"
    }
]
```

- Atalhos úteis
  - Ctrl + Shift + i (auto-indent)
  - Ctrl + p (Pesquisa de arquivo)
  - Ctrl + Shift + p (pesquisa de comandos)
  - Ctrl + Shift + f (pesquisa global de palavras)
  - Ctrol + f (pesquisa por palavra no arquivo aberto)

---
