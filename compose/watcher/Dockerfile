FROM node:16

LABEL mantainer "hacklab/ <contato@hacklab.com.br>"

COPY compose/watcher/entrypoint.sh /entrypoint.sh

ENTRYPOINT /entrypoint.sh

RUN chmod +x /entrypoint.sh

RUN mkdir /app

WORKDIR /app
