from flask import Flask, request, jsonify
import joblib, os
import requests
from datetime import datetime
from dotenv import load_dotenv

# Load DB settings from .env
load_dotenv()
conn_info = dict(
    dbname=os.getenv('PG_DB','warehouse_db'),
    user=os.getenv('PG_USER','postgres'),
    password=os.getenv('PG_PASS','pass'),
    host=os.getenv('PG_HOST','localhost'),
    port=int(os.getenv('PG_PORT','5433'))
)

app = Flask(__name__)

# Load ML model
model = joblib.load("demand_model.pkl")

# Laravel API endpoints
LARAVEL_API = "http://127.0.0.1:8000/api"

@app.route('/')
def health():
    return {"message": "Flask microservice is running!"}, 200

# RFID Scan Endpoint
@app.route("/rfid-scan", methods=["POST"])
def scan_barcode():
    data = request.json or {}

    # Add timestamp
    scan_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    payload = {
        "sku": data["sku"],
        "location": data["location"],
        "status": data["status"],
        "last_scanned": scan_time
    }

    return jsonify({"data": payload}), 200

# Sensor Data Endpoint
@app.route("/sensor-alert", methods=["POST"])
def scan_alert():
    data = request.json or {}
    sku = data.get("sku")
    stock = int(data.get("stock", 0))

    if stock <= 0:
        alert_status = "No Stock"
    elif stock <= 10:
        alert_status = "Limited Stock"
    elif stock <= 30:
        alert_status = "Low Stock"
    else:
        alert_status = "In Stock"

    scan_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    payload = {
        "sku": sku,
        "stock": stock,
        "alert": alert_status,
        "created_at": scan_time
    }

    return jsonify({"data": payload}), 200
    

# Predict Demand Endpoint
@app.route("/predict-demand", methods=["POST"])
def predict_demand():
    data = request.json or {}

    prev_month_num = int(data.get("previous_month_number"))
    prev_month_sales = float(data.get("previous_month_sales"))
    sku = data.get("sku", "sku-001")

    # Predict demand using ML model
    X = [[prev_month_num, prev_month_sales]]
    pred = model.predict(X)[0]
    pred = round(float(pred), 2)

    payload = {
        "sku": data["sku"],
        "month": data["month"],
        "sales": data["sales"],
        "predicted_demand": pred
    }

    return jsonify({"data": payload}), 200

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5001)
