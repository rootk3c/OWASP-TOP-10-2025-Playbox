FROM python:3.10-alpine

WORKDIR /app

# 1. Create the dummy flag
COPY flag.txt /root/flag.txt

# 2. Copy the fake registry and install the malicious package locally FIRST
COPY ./fake_registry/reqeusts /tmp/reqeusts
# Use --no-cache-dir to keep the Alpine image as small as possible
RUN pip install --no-cache-dir /tmp/reqeusts

# 3. Copy the actual app code
COPY ./src /app

# 4. Install the rest of the app's requirements
RUN pip install --no-cache-dir -r requirements.txt

# 5. Run the app using gunicorn for a production feel
CMD ["gunicorn", "-w", "4", "-b", "0.0.0.0:5000", "app:app"]