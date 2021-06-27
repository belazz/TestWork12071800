FROM node:latest as build-stage
WORKDIR /front
COPY package*.json ./
RUN yarn install
COPY ./ .
RUN yarn run build

FROM nginx as production-stage
RUN mkdir /front
COPY --from=build-stage /front/dist /front
COPY docker/nginx.conf /etc/nginx/nginx.conf
