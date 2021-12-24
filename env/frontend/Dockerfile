FROM node:lts-alpine

ENV APP_ROOT /web
WORKDIR ${APP_ROOT}
COPY ./package.json ./
RUN npm install

CMD ["npm", "run", "serve"]