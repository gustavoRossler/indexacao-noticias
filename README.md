## Comandos no console para rodar o projeto

### 1 - Clonar repositório
```bash
git clone https://github.com/gustavoRossler/indexacao-noticias.git
```

### 2 - Acessar o diretório do projeto clonado
```bash
cd indexacao-noticias
```
(Sendo indexacao-noticias o nome da pasta criada)

### 3 - Subir os containers 
```bash
docker-compose up -d
```
(O SO pode pedir permissão para acessar os diretórios dos volumes criados nessa etapa)

### 4 - Rodar o composer para instalar os módulos PHP e scripts necessários
```bash
docker-compose exec app composer install
docker-compose exec app composer run-script post-root-package-install
docker-compose exec app composer run-script post-create-project-cmd
```

### Opcional
Pode rodar os tests para ver se está tudo funcionando
```bash
docker-compose exec app php artisan test
```

### Execução

Rota para fazer o upload do arquivo Json

http://localhost:8080/api/upload-file

Método POST

Parâmetros:
- file (tipo: file, aceita: arquivos .json)



----------------------------------------------------------------------------

Aceita arquivos tipo Json, com o template do arquivo enviado nas instruções.

Adicionei uma rota na mesma aplicação para importar os dados para o elasticsearch.

Após fazer upload do arquivo basta acessar o endereço:

http://localhost:8080/api/importer/import-data

Método GET


----------------------------------------------------------------------------

Endereço para visualizar os documentos importados

http://localhost:8080/news
