FROM python:3.10-alpine

# Security: Prevent Python from writing .pyc files and buffering stdout
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1

# Create a non-root user
RUN adduser -D ctfuser

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY app.py .
COPY templates/ templates/

# Secure permissions
RUN chown -R ctfuser:ctfuser /app
USER ctfuser

EXPOSE 8004

CMD ["python", "app.py"]

