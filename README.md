# Concordamos Plugin

## Desenvolvimento

O docker-compose,yml desse projeto tem apenas o compilador dos assets, para desenvolvimento, em outro repositório com o docker do WordPress adicione nos volumes do serviço WordPress, a seguinte instrução:

```bash
- ../concordamos-plugin/trunk:/var/www/html/wp-content/plugins/concordamos
```

E rode `docker-compose up` nos dois projetos.

## Traduções

Para gerar os arquivos POT, rode dentro da pasta do plugin o seguinte comando:

```bash
wp i18n make-pot . languages/concordamos.pot
```
