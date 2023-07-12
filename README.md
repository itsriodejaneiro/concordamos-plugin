# Concordamos Plugin

## Desenvolvimento

O docker-compose,yml desse projeto tem apenas o compilador dos assets, para desenvolvimento, em outro repositório com o docker do WordPress adicione nos volumes do serviço WordPress, a seguinte instrução:

```
- ../concordamos-plugin/trunk:/var/www/html/wp-content/plugins/concordamos
```

E rode `docker-compose up` nos dois projetos.
