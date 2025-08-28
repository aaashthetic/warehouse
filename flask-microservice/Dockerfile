FROM python:3.11-slim

WORKDIR /app

# Install system dependencies for scikit-learn & pandas
RUN apt-get update && apt-get install -y \
    build-essential libopenblas-dev gfortran wget \
    && rm -rf /var/lib/apt/lists/*

# Copy requirements
COPY requirements.txt ./

# Upgrade pip and install dependencies
RUN pip install --upgrade pip \
    && pip install --no-cache-dir -r requirements.txt

# Copy the rest of the app
COPY . .

EXPOSE 5001

CMD ["flask", "run", "--host=0.0.0.0", "--port=5001"]