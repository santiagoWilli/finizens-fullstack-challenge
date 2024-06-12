FROM node:20-alpine

WORKDIR /app

# Copiar los archivos de configuración del paquete npm y luego instalar dependencias
COPY ./apps/PortfolioManager/gui/package*.json .
RUN npm install
COPY ./apps/PortfolioManager/gui .

# Ejecutar el comando vue-cli-service directamente desde node_modules
CMD ["./node_modules/.bin/vue-cli-service", "serve"]