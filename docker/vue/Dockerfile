FROM node:20-alpine

WORKDIR /app

COPY ./apps/PortfolioManager/gui/package*.json .
RUN npm install
COPY ./apps/PortfolioManager/gui .

CMD ["./node_modules/.bin/vue-cli-service", "serve"]