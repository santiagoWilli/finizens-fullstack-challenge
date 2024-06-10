# syntax=docker/dockerfile:1

FROM node:20-alpine

COPY ./apps/PortfolioManager/gui /app
WORKDIR /app

RUN npm install -g @vue/cli
RUN npm install
RUN npm run build

EXPOSE 8080
CMD ["npm", "run", "serve"]