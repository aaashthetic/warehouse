"use client";

import { useEffect, useState } from "react";
import InventoryChart from "../components/InventoryChart";

export default function Dashboard() {

  const [products, setProducts] = useState([]);
  const [inventory, setInventory] = useState([]);
  const [demand, setDemand] = useState([]);
  const [rfidLogs, setRfidLogs] = useState([]);

  useEffect(() => {

    fetch("http://127.0.0.1:8000/api/products")
    .then((res) => res.json())
    .then((data) => {
        console.log("Products API response:", data);
        setProducts(data || [])
    });

    fetch("http://127.0.0.1:8000/api/inventory-alerts")
        .then((res) => res.json())
        .then((data) => {
            console.log("Alerts API response:", data);
            setInventory(data || [])
        });

    fetch("http://127.0.0.1:8000/api/sales")
        .then((res) => res.json())
        .then((data) => {
            console.log("Sales API response:", data);
            setDemand(data || [])
        });

    fetch("http://127.0.0.1:8000/api/rfid-logs")
        .then((res) => res.json())
        .then((data) => {
            console.log("Logs API response:", data);
            setRfidLogs(data || [])
        });

  }, []);

  return (
      <div className="p-10">
        <h1 className="text-2xl font-bold mb-6 text-center">
          🏭 Warehouse Inventory Dashboard
        </h1>

        <InventoryChart
          products={products}
          inventory={inventory}
          demand={demand}
          rfidLogs={rfidLogs}
        />
      </div>
  );
}
