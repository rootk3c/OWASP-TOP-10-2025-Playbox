FROM python:3.12-alpine

WORKDIR /app

# Install dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy app
COPY . .

# Create non-root user
RUN adduser -D medibot
USER medibot

EXPOSE 5000

# Run with Gunicorn for production simulation
CMD ["gunicorn", "--bind", "0.0.0.0:5000", "--workers", "2", "app:app"]

