FROM python:3.11-alpine

WORKDIR /app

# Install dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application source
COPY src/ .

# Initialize DB
RUN python init_db.py

# Create non-root user
RUN adduser -D ctf
USER ctf

EXPOSE 5000
CMD ["python", "app.py"]