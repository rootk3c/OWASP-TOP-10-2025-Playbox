FROM python:3.10-alpine

# Set working directory
WORKDIR /app

# Install dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application files
COPY app/ .

# Ensure the logs directory is writable by the application
RUN chmod 777 /app/logs
RUN chmod 666 /app/logs/debug.log

# Expose the requested port
EXPOSE 8009

# Run the application
CMD ["python", "app.py"]
