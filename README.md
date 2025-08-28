# 🏭 Warehouse Inventory Dashboard

A full-featured warehouse inventory dashboard built with **Next.js**, **Chart.js**, and **Metabase**, designed to visualize real-time inventory, RFID scan logs, alerts, and forecasted demand.

---

## ✨ Features

- 📊 **Dashboard Cards:** Displays key warehouse metrics such as total products, stock levels, alerts, and recent RFID scans.
- 📦 **Inventory Bar Chart:** Visualizes current stock levels per product with color-coded indicators.
- 📈 **Sales Demand Predictor:** Line chart forecasting monthly sales demand.
- 📝 **RFID Scan Logs Table:** Shows product scan data including zone and timestamp.
- 🌡️ **Heatmap Visualization:** Product movement density per zone using embedded Metabase dashboards.
- 📱 **Responsive Design:** Fully responsive and mobile-friendly interface.

---

## 🖼️ Sample Visualizations

| Chart Type     | Data Source       | Description                        |
| -------------- | ----------------- | ---------------------------------- |
| **Bar Chart**  | `sensor-alert`    | Current inventory levels           |
| **Line Chart** | `/predict-demand` | Monthly forecasted demand          |
| **Table**      | `rfid-scan logs`  | Zone, timestamp, product scan data |
| **Heatmap**    | `rfid_logs`       | Product movement density per zone  |

---

## 🛠️ Tech Stack

- **Next.js:** Frontend framework for React-based interactive dashboard
- **Chart.js:** Custom chart visualizations integrated in the frontend
- **Metabase:** Low-code dashboards for analytics and heatmap visualizations
- **PostgreSQL:** Database for storing product, inventory, and RFID data
- **Flask API:** Handles sensor, demand, and RFID scan data and exposes endpoints
- **Laravel:** Backend for storing and routing data to PostgreSQL
- **🐳 Docker:** Containerized environment for consistent development and deployment

---

## ⚡ Installation & Setup

### Prerequisites

- Docker & Docker Compose installed
- Node.js & npm installed (if running outside Docker)
- PostgreSQL database configured

### Running with Docker

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd warehouse-inventory-dashboard

   ```

2. Build and start Docker containers:

   ```bash
   docker-compose up --build

   ```

3. Access the dashboard on http://localhost:3000 (frontend) and Metabase on http://localhost:3001

### Running Locally (without Docker)

1. Install dependencies:

   ```bash
   npm install

   ```

2. Build the Next.js frontend:

   ```bash
   npm run build
   npm run start

   ```

3. Make sure Flask and Laravel backends are running and PostgreSQL is accessible.

---

## 📂 Project Structure

warehouse/
├── backend/ # Laravel backend
│ ├── app/ # Models, controllers
│ ├── database/ # Migrations & seeders
│ ├── routes/ # API routes
│ └── ... # Config, middleware, etc.
│
├── frontend/ # Next.js frontend
│ ├── components/ # Reusable components
│ │ └── InventoryChart.js
│ ├── pages/ # Page components
│ │ └── dashboard.js
│ └── ... # Styles, utils, etc.
│
├── flask-microservice/ # Flask API & ML service
│ ├── app.py # API endpoints
│ └── demand_model_train.py# Demand forecasting model training
│
├── data/ # Dataset files
│ └── \*.csv
│
├── docker-compose.yml # Docker setup for all services
└── README.md # Project documentation

---

## Team 1

👩‍💻 Ashley Egera
👨‍💻 James Tristan Landa
👨‍💻 Kharl Chester Velasco
