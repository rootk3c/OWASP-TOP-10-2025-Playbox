# Use the highly lightweight Alpine Linux Node image
FROM node:18-alpine

# Set the working directory
WORKDIR /usr/src/app

# Copy dependency files first
COPY package*.json ./

# Install dependencies (using npm install since we don't have a package-lock.json yet)
# and clean cache to reduce image size
RUN npm install --omit=dev && npm cache clean --force

# Copy the application source code
COPY . .

# Expose the internal container port
EXPOSE 8006

# Run as an unprivileged user for security
USER node

# Start the application
CMD [ "node", "app.js" ]